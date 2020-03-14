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
use Illuminate\Support\Facades\Gate;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class CourseService implements ICourseService
{
    use Utils;

    /**
     * @inheritDoc
     */
    function get(int $id, bool $extra = false): Course
    {
        $course = $extra ? Course::with(['units'])->findOrFail($id) : Course::findOrFail($id);
        if (!Gate::allows('view', $course) && !Gate::allows('viewAny', Course::class))
        {
            throw new ForbiddenApiException("You are not allowed to view this course");
        }
        return $course;
    }

    /**
     * @inheritDoc
     */
    function paginate($size = 15)
    {
        $this->throwForbiddenIfNotAllowed('viewAny', Course::class, "You are not allowed to view all courses");

        return Course::query()
            ->select('courses.name', 'courses.price', 'courses.id', 'courses.sign_up_beg', 'courses.sign_up_end',
                DB::raw('COUNT(units.id) as units_count'), DB::raw('COUNT(lessons.id) as lessons_count'))
            ->leftJoin('units', 'units.course_id', '=', 'courses.id')
            ->leftJoin('lessons', 'lessons.unit_id', '=', 'units.id')
            ->groupBy('courses.id')
            ->paginate($size);
    }

    /**
     * @inheritDoc
     */
    function update(Course $course, $data): bool
    {
        $this->throwForbiddenIfNotAllowed('update', $course, "You are not allowed to update this course");
        return $course->update($data);
    }

    /**
     * @inheritDoc
     */
    function delete(Course $course, bool $force = false): ?bool
    {
        try {
            if ($force)
            {
                $this->throwForbiddenIfNotAllowed('forceDelete', $course, "You are not allowed to force-delete this course");
                return $course->forceDelete();
            }
            else
            {
                $this->throwForbiddenIfNotAllowed('delete', $course, "You are not allowed to delete this course");
                return $course->delete();
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    function create($data): Course
    {
        $this->throwForbiddenIfNotAllowed('create', Course::class, "You are not allowed to create a course");
        return Course::create($data);
    }

    /**
     * @inheritDoc
     */
    function updateCourseUnits(Course $course, ICourseUnitsPayload $data): ICourseUnitsUpdateResponse
    {
        $this->throwForbiddenIfNotAllowed('update', $course, "You are not allowed to update this course");

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
