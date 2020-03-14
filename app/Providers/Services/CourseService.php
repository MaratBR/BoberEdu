<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\Providers\Services\Abs\ICourseService;
use App\Providers\Services\Abs\ICourseUnitsUpdateResponse;
use App\Purchase;
use App\Unit;
use App\User;
use Illuminate\Support\Facades\DB;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class CourseService implements ICourseService
{

    function get(int $id, bool $extra = false): Course
    {
        return $extra ? Course::with(['units'])->findOrFail($id) : Course::findOrFail($id);
    }

    function paginate($size = 15)
    {
        return Course::query()
            ->select('courses.name', 'courses.price', 'courses.id', 'courses.sign_up_beg', 'courses.sign_up_end',
                DB::raw('COUNT(units.id) as units_count'), DB::raw('COUNT(lessons.id) as lessons_count'))
            ->leftJoin('units', 'units.course_id', '=', 'courses.id')
            ->leftJoin('lessons', 'lessons.unit_id', '=', 'units.id')
            ->groupBy('courses.id')
            ->paginate($size);
    }

    function update(Course $course, $data): bool
    {
        return $course->update($data);
    }

    function delete(Course $course): ?bool
    {
        try {
            return $course->delete();
        } catch (\Exception $e) {
            return null;
        }
    }

    function create($data): Course
    {
        return Course::create($data);
    }

    function updateCourseUnits(Course $course, ICourseUnitsPayload $data): ICourseUnitsUpdateResponse
    {
        if ($data === [])
            throw new UnprocessableEntityHttpException("Message body is empty");

        $units = [];
        foreach ($course->units as $unit)
        {
            $units[$unit->id] = $unit;
        }

        $toBeDeleted = $data->getDeleted();
        $new = $data->getNew();
        $order = $data->getOrder();
        $upd = $data->getUpdated();
        $upd = array_filter($upd, function ($v) use ($toBeDeleted, $upd) {
            return !in_array($v['id'], $toBeDeleted) &&
                isset($units, $v['id']) &&
                array_keys($v) !== ['id'];
        });
        $updById = [];

        foreach ($upd as $r)
        {
            $updById[$r['id']] = $r;
        }

        $orderInv = [];
        $orderXCounter = count($order);

        foreach ($order as $ii => $i)
        {
            $orderInv[$i] = $ii;
            if (substr($i, 0, 1) === 'n')
            {
                if (substr($i, 1) >= count($new))
                    throw new BadRequestApiException('Invalid order item: ' . $i . ' no corresponding unit defined in "new"');
            }
        }

        $newUnits = [];

        foreach ($new as $i => $nu) {
            $nu['order_num'] = $orderInv["n$i"] ?? ($orderXCounter++);
            $newUnits[] = new Unit($nu);
        }

        $course->units()->saveMany($newUnits);
        $updated = [];

        DB::beginTransaction();

        foreach ($units as $unit) {
            if (array_key_exists($unit['id'], $updById)) {
                $updData = $updById[$unit['id']];
                $updated[] = $unit['id'];
            } else {
                $updData = [];
            }
            $updData['order_num'] = $orderInv[$unit['id']] ?? ($orderXCounter++);
            Unit::query()
                ->where('id', '=', $unit->id)
                ->update($updData);
        }

        if ($toBeDeleted)
            Unit::query()->whereIn('id', $toBeDeleted)->delete();

        DB::commit();


        return new UnitsUpdateResponse(
            array_map(function (Unit $unit) { return $unit->id; }, $newUnits),
            $updated,
            $toBeDeleted
        );
    }


}
