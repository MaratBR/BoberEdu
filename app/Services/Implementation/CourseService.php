<?php


namespace App\Services\Implementation;


use App\Category;
use App\Course;
use App\Exceptions\ThrowUtils;
use App\Lesson;
use App\Rate;
use App\Services\Abs\ICourseService;
use App\Services\Abs\ICourseUnitsUpdateResponse;
use App\Unit;
use App\User;
use App\Utils\Convert;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class CourseService implements ICourseService
{
    use ThrowUtils;

    /**
     * @inheritDoc
     */
    function get(int $id): Course
    {
        /** @var Course $course */
        $course = Course::with(['units' => function (HasMany $q) {
            $q->orderBy('order_num');
        }])->findOrFail($id);

        return $course;
    }

    function search(string $query, ?int $categoryId = null): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator $p */
        $p = Course::search(Convert::escapeElasticReservedChars($query))->paginate();
        return $p;
    }

    function getBy(array $params): Course
    {
        /** @var Course $c */
        $c = Course::query()->where($params)->firstOrFail();
        return $c;
    }


    function getAvailable(int $id, bool $extra = false): Course
    {
        /** @var Course $course */
        $course = Course::with(['units' => function (HasMany $q) {
            $q->orderBy('order_num');
        }])->when('available', '=', true)->findOrFail($id);

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
        $course = Course::create($data);
        $course->refresh();
        return $course;
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

    function putLessonsOrder(int $id, array $data)
    {
        /** @var Course $course */
        $course = Course::query()
            ->select(['id'])
            ->with([
                'units' => function (HasMany $q) {
                    $q->select(['id', 'course_id'])->with([
                        'lessons' => function (HasMany $q) {
                            $q->orderBy('order_num')->select(['order_num', 'id', 'unit_id']);
                        }
                    ]);
                }
            ])
            ->findOrFail($id);
        $d = $data['units'] ?? null;
        $units = [];
        foreach ($d as $dd) {
            $units[$dd['id']] = $dd['order'];
        }

        $this->throwErrorIf(500, "Invalid request payload", $units === null);

        if (count($units) === 0)
            return;

        DB::beginTransaction();

        foreach ($course->units as $unit) {
            $order = $units[$unit->id] ?? null;
            if ($order === null)
                continue;

            $lessonsIds = collect($unit->lessons)->map(function (Lesson $l) { return $l->id; });
            $order = $units[$unit->id];
            $order = array_filter($order, function (int $id) use ($lessonsIds) { return $lessonsIds->contains($id); });
            if (count($order) !== count($lessonsIds)) {
                DB::rollBack();
                $this->throwError(422, "Inconsistent count of lessons in request");
            }

            foreach($lessonsIds as $lessonId) {
                if (array_search($lessonId, $order) === false)
                    $order[] = $lessonId;
            }

            for ($i = 0; $i < count($order); $i++) {
                Lesson::query()
                    ->where('id', '=', $order[$i])
                    ->update(['order_num' => $i]);
            }
        }

        DB::commit();
    }

    function getWithUnits(int $id)
    {
        return Course::with('units')->findOrFail($id);
    }

    function getWithOverview(int $id)
    {
        return Course::with([
            'units' => function (HasMany $b) {
                $b->select('name', 'id', 'course_id', 'about', 'order_num', 'preview');
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



    private function paginationModifier(Builder $builder, int $size)
    {
        return $builder
            ->select('courses.*')
            ->with('units.lessons')
            ->paginate($size);
    }

    //#region Categories

    function getCategory(int $categoryId): Category
    {
        return Category::findOrFail($categoryId);
    }

    function getPopular(int $categoryId)
    {
        return Course::query()->inRandomOrder()->limit(5)->get();
    }

    function createCategory(array $data): Category
    {
        $cat = Category::create($data);
        $cat->refresh();
        return $cat;
    }

    function paginateInCategory(Category $category, int $size = 15): LengthAwarePaginator
    {
        return $this->paginationModifier(
            Course::query()->where('category_id', '=', $category->id),
            $size
        );
    }

    //#endregion

    //#region Course rates

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

    //#endregion

    //#region Units

    public function getUnit(int $unitId): Unit
    {
        /** @var Unit $u */
        $u = Unit::query($unitId)->with('course')->findOrFail($unitId);
        return $u;
    }


    //#endregion
}
