<?php


namespace App\Services\Implementation;


use App\Category;
use App\Course;
use App\Exceptions\ThrowUtils;
use App\Rate;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ICourseUnitsUpdateResponse;
use App\Unit;
use App\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        }])->find($id) : Course::find($id);

        $this->throwNotFoundIfNull($course, "Course not found");

        if (!Gate::allows('view', $course) && !Gate::allows('viewAny', Course::class)) {
            throw new ForbiddenApiException("You are not allowed to view this course");
        }
        return $course;
    }

    /**
     * @inheritDoc
     */
    function paginateWithExtra($size = 15)
    {
        return $this->paginationModifier(Course::query(), $size);
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
                    throw new HttpException(422, 'Invalid order item: ' . $i . ' no corresponding unit defined in "new"');
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
        $course = Course::with([
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
        ])->find($id);
        $this->throwNotFoundIfNull($course, "Course not found");

        return $course;
    }

    function getWithUnits(int $id)
    {
        return Course::with('units')->findOrFail($id);
    }

    function getWithOverview(int $id)
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
            },
            'teachers'
        ])->findOrFail($id);
    }

    function getTrialDays(int $id): int
    {
        /** @var Course $course */
        $course = Course::query()->select(['trial_length'])->findOrFail($id);

        return $course->trial_length;
    }

    function paginateInCategory(Category $category, int $size = 15): LengthAwarePaginator
    {
        return $this->paginationModifier(
            Course::query()->where('category_id', '=', $category->id),
            $size
        );
    }

    private function paginationModifier(Builder $builder, int $size)
    {
        return $builder
            ->select('courses.*', DB::raw('COUNT(units.id) as units_count'), DB::raw('COUNT(lessons.id) as lessons_count'))
            ->leftJoin('units', 'units.course_id', '=', 'courses.id')
            ->leftJoin('lessons', 'lessons.unit_id', '=', 'units.id')
            ->groupBy('courses.id')
            ->paginate($size);
    }

    function getCategory(int $categoryId): Category
    {
        return Category::findOrFail($categoryId);
    }

    function getPopular(int $categoryId)
    {
        return Course::query()->inRandomOrder()->limit(5)->get();
    }

    function getAllCategories()
    {
        return Category::all();
    }

    function removeRate(Course $course, User $user)
    {
        Rate::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $course->id)
            ->delete();
    }

    function setRate(Course $course, User $user, float $value)
    {
        $query = Rate::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $course->id);
        $exists = $query->exists();

        if ($exists) {
            $query->update([
                'value' => $value
            ]);
        } else {
            Rate::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'value' => $value
            ]);
        }
    }

    function getRate(User $user, int $courseId): ?float
    {
        $rate = Rate::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $courseId)
            ->first();

        if ($rate == null)
            return null;

        $rate = $rate->value;
        $rate = (float)$rate;
        $rate /= 2;
        return $rate;
    }

}
