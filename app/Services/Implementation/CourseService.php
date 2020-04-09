<?php


namespace App\Services\Implementation;


use App\Course;
use App\Exceptions\ThrowUtils;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ICourseUnitsUpdateResponse;
use App\Unit;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ForbiddenApiException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class CourseService implements ICourseService
{
    use ThrowUtils;

    /**
     * @inheritDoc
     */
    function get(int $id, bool $extra = false): Course
    {
        $course = $extra ? Course::with(['units' => function (HasMany $q) {
            $q->orderBy('order_num');
        }])->findOrFail($id) : Course::findOrFail($id);
        if (!Gate::allows('view', $course) && !Gate::allows('viewAny', Course::class)) {
            throw new ForbiddenApiException("You are not allowed to view this course");
        }
        return $course;
    }

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
    function update(Course $course, $data): bool
    {
        return $course->update($data);
    }

    /**
     * @inheritDoc
     */
    function delete(Course $course): ?bool
    {
        try {
            return $course->delete();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    function create($data): Course
    {
        return Course::create($data);
    }

    /**
     * @inheritDoc
     */
    function updateCourseUnits(Course $course, ICourseUnitsPayload $data): ICourseUnitsUpdateResponse
    {
        if ($data === [])
            throw new UnprocessableEntityHttpException("Message body is empty");

        $units = [];
        foreach ($course->units as $unit) {
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

        foreach ($upd as $r) {
            $updById[$r['id']] = $r;
        }

        $orderInv = [];
        $orderXCounter = count($order);

        foreach ($order as $ii => $i) {
            $orderInv[$i] = $ii;
            if (substr($i, 0, 1) === 'n') {
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
            array_map(function (Unit $unit) {
                return $unit->id;
            }, $newUnits),
            $updated,
            $toBeDeleted
        );
    }

    function getWithUnitsAndLessonsNames(int $id)
    {
        return Course::with([
            'units' => function (HasMany $b) {
                $b->select('name', 'id', 'course_id', 'about', 'order_num', 'is_preview');
                $b->orderBy('order_num');
                $b->with([
                    'lessons' => function (HasMany $b) {
                        $b->orderBy('order_num');
                        $b->select('id', 'unit_id', 'title', 'order_num');
                    }
                ]);
            }
        ])->findOrFail($id);
    }

    function getWithUnits(int $id)
    {
        return Course::with('units')->findOrFail($id);
    }
}
