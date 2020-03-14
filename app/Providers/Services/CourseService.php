<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\Purchase;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Lanin\Laravel\ApiExceptions\BadRequestApiException;
use Lanin\Laravel\ApiExceptions\ConflictApiException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class UnitsUpdateResponse implements ICourseUnitsUpdateResponse
{
    private $newIds;
    private $updated;
    private $deleted;

    public function __construct($newIds, $updated, $deleted)
    {
        $this->deleted = $deleted;
        $this->newIds = $newIds;
        $this->updated = $updated;
    }

    /**
     * @inheritDoc
     */
    public function toJson($options = 0)
    {
        return json_encode([
            'created' => $this->newIds,
            'deleted' => $this->deleted,
            'updated' => $this->updated
        ], $options);
    }
}

class AttendanceStatus implements IAttendanceStatus
{
    private $attendance;
    private $previewDurationInDays;

    public function __construct(?CourseAttendance $attendance, int $previewDurationInDays)
    {
        $this->attendance = $attendance;
        $this->previewDurationInDays = $previewDurationInDays;
    }

    function hasAccess(): bool
    {
        return ($this->isPreview() && !$this->isExpired()) ||
            $this->getPurchaseInnerStatus() === Purchase::STATUS_SUCCESSFUL;
    }

    function isPreview(): bool
    {
        return $this->exists() && $this->attendance->preview;
    }

    function isExpired(): bool
    {
        return $this->exists() && $this->addPreviewPeriod($this->attendance->created_at)->gt(Carbon::now());
    }

    function asString(): string
    {
        if (!$this->exists())
            return ATTENDANCE_STATUS_NO;
        elseif ($this->isPreview())
            if ($this->isExpired())
                return ATTENDANCE_STATUS_PREVIEW_EXPIRED;
            else
                return ATTENDANCE_STATUS_PREVIEW;
        else
        {
            $innerStatus = $this->getPurchaseInnerStatus();

            switch ($innerStatus)
            {
                case null:
                case Purchase::STATUS_PENDING:
                    return ATTENDANCE_STATUS_AWAITING_PAYMENT;
                case Purchase::STATUS_SUCCESSFUL:
                    return ATTENDANCE_STATUS_YES;
                default:
                    return ATTENDANCE_STATUS_CANCELLED;
            }

        }
    }

    function getPurchaseInnerStatus(): ?string
    {
        return $this->exists() ?
            ($this->attendance->purchase ? $this->attendance->purchase->status : null) :
            null;
    }

    private function addPreviewPeriod(Carbon $createdAt): Carbon
    {
        return $createdAt->addDays($this->previewDurationInDays);
    }

    private function exists(): bool
    {
        return $this->attendance !== null;
    }

    function hasPayment(): bool
    {
        return $this->getPurchaseInnerStatus() === Purchase::STATUS_PENDING;
    }
}

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

    function attend(Course $course, User $user, ICourseAttendanceInfo $info): CourseAttendance
    {
        $giftTo = $info->giftTo();

        if ($giftTo !== null)
        {
            if ($info->isPreview())
                throw new BadRequestApiException("You cannot gift a preview course");
            $giftTo = User::findOrFail($giftTo);
        }

        $userId = $giftTo ? $giftTo->id : $user->id;
        $giftBy = $giftTo ? $user->id : null;

        if (CourseAttendance::hasRecord($userId, $course->id))
        {
            throw new ConflictApiException(
                $giftTo ? "This user already attend this course" : "You already attend this course");
        }


        $attendance = new CourseAttendance([
            'user_id' => $userId,
            'gifted_by_id' => $giftBy,
            'course_id' => $course->id,
            'preview' => $info->isPreview()
        ]);

        $attendance->save();
        $attendance->refresh();

        return $attendance;
    }

    private function addPreviewPeriod(Carbon $date)
    {
        return $date->addDays(7);
    }

    function attendanceStatus(Course $course, User $user): IAttendanceStatus
    {
        $attendance = CourseAttendance::query()
            ->where('user_id', '=', $user->id)
            ->where('course_id', '=', $course->id)
            ->first();

        return new AttendanceStatus($attendance, 7);
    }
}
