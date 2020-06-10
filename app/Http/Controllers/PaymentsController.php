<?php

namespace App\Http\Controllers;

use App\Http\DTO\PaymentDto;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Payments\CreatePaymentRequest;
use App\Payment;
use App\Services\Abs\ICourseService;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\IPaymentsService;

class PaymentsController extends Controller
{
    private $courses;
    private $payments;
    private $enrollments;

    public function __construct(ICourseService $courses, IEnrollmentService $enrollmentService, IPaymentsService $payments)
    {
        $this->courses = $courses;
        $this->payments = $payments;
        $this->enrollments = $enrollmentService;
    }

    function create(CreatePaymentRequest $request, int $courseId)
    {
        $gateaway = $request->getGateaway();
        $this->throwErrorIf(422, "Invalid/unsupported gateaway", !$this->payments->hasGateaway($gateaway));

        $data = $request->getData();
        $course = $this->courses->get($courseId);
        $this->throwForbiddenIfNotAllowed('buy', $course);

        $user = $request->user();
        $enrollment = $this->enrollments->getEnrollmentRecord($courseId, $user);

        $this->throwErrorIf(400, "You already  have access to this course", $enrollment->activated);

        $created = false;
        $payment = $enrollment->payment;

        if ($payment == null || $payment->is_failed) {
            $payment = $this->payments->createPayment($course, $request, $user, $gateaway, $data);
            $enrollment->update([
                'payment_id' => $payment->id
            ]);
        }

        if ($payment->is_successful) {
            $this->enrollments->activate($enrollment);
        } elseif ($payment->is_pending) {
            $status = $this->payments->externalPaymentStatus($payment);

            if ($status === true) {
                $payment->update(['status' => Payment::STATUS_SUCCESSFUL]);
            } elseif ($status === false) {
                $payment->update(['status' => Payment::STATUS_CANCELLED]);
            }
        }

        $dto = new PaymentDto($payment);
        $status = 200;
        if ($created)
            $status = 201;
        if ($payment->is_failed)
            $status = 400;
        return response()->json($dto, $status);
    }

    function get(AuthenticatedRequest $request, int $courseId)
    {
        $payment = $this->enrollments->getEnrollmentRecord($courseId, $request->user())->payment;
        $this->throwNotFoundIfNull($payment, "Payment not found");

        return new PaymentDto($payment);
    }
}
