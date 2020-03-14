<?php


namespace App\Providers\Services;


use App\Course;
use App\CourseAttendance;
use App\Purchase;
use App\User;

class PurchaseService implements IPurchasesService
{
    private $externalPaymentService;

    public function __construct(IExternalPaymentService $externalPaymentService)
    {
        $this->externalPaymentService = $externalPaymentService;
    }

    function get(int $id): Purchase
    {
        return Purchase::findOrFail($id);
    }

    function create(string $title, string $redirect, float $price,
                    CourseAttendance $course, User $recipient): Purchase
    {
        $externalPaymentId = $this->externalPaymentService->placePayment($price, $title, $redirect);

        return Purchase::create([
            'external_id' => $externalPaymentId,
            'status' => 'pending',
            'user_id' => $recipient->id,
            'price' => $price,
            'course_attendance_id' => $course->id
        ]);
    }
}
