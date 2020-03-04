<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\CreateNewCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\UpdateCourseUnitsRequest;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController
{
    use CRUDTrait;

    public function __construct()
    {
        $this->eloquentModel = Course::class;
    }

    protected function byIdWithDetails($id)
    {
        return $this->getQueryBuilder()
            ->with([
                'units' => function ($q) {
                    $q->orderBy('order_num', 'ASC');
                }
            ])
            ->findOrFail($id);
    }

    protected function getAll(): Builder
    {
        return $this->getQueryBuilder()
            ->select('courses.name', 'courses.price', 'courses.id', 'courses.sign_up_beg', 'courses.sign_up_end',
                DB::raw('COUNT(units.id) as units_count'), DB::raw('COUNT(lessons.id) as lessons_count'))
            ->leftJoin('units', 'units.course_id', '=', 'courses.id')
            ->leftJoin('lessons', 'lessons.unit_id', '=', 'units.id')
            ->groupBy('courses.id');
    }

    public function show(Request $request)
    {
        return $this->byIdWithDetails($request->course);
    }

    public function update(UpdateCourseRequest $request)
    {
        $this->updById($request->course, $request->validated());
        return response()->noContent();
    }

    public function destroy(Request $request)
    {
        $this->delById($request->course);
    }

    public function store(CreateNewCourseRequest $request)
    {
        return $request->validated();
        $course = $this->create($request->validated());
        return response()->json($course, 201);
    }

    public function index(Request $request)
    {
        return $this->paginate($request, $this->getAll());
    }

    public function updateUnits(UpdateCourseUnitsRequest $request)
    {
        $data = $request->validated();
        if ($data === [])
            return response()->noContent();

        /** @var Course $course */
        $course = $this->byId($request->course);
        $units = [];
        foreach ($course->units as $unit)
        {
            $units[$unit->id] = $unit;
        }

        $toBeDeleted = $data['delete'] ?? [];
        $new = $data['new'] ?? [];
        $order = $data['order'] ?? [];
        $upd = $data['upd'] ?? [];
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
                    return response()->json(['message' => 'Invalid order item: ' . $i . ' no corresponding unit defined in "new"']);
            }
        }

        $newUnits = [];

        foreach ($new as $i => $nu) {
            $nu['order_num'] = $orderInv["n$i"] ?? ($orderXCounter++);
            $newUnits[] = new Unit($nu);
        }

        $course->units()->saveMany($newUnits);


        foreach ($units as $unit) {
            $updData = $updById[$unit['id']] ?? [];
            $updData['order_num'] = $orderInv[$unit['id']] ?? ($orderXCounter++);
            $unit->update($updData);
        }

        Unit::destroy($toBeDeleted);

        return [
            'deleted' => $toBeDeleted,
            'created' => $newUnits
        ];
    }
}
