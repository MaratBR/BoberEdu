<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\Payments\InitPaymentRequest;
use App\Providers\Services\IExternalPaymentService;
use App\Providers\Services\ICourseService;
use App\Providers\Services\IPurchasesService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private $purchases;
    private $courses;

    public function __construct(IPurchasesService $service, ICourseService $courseService)
    {
        $this->purchases = $service;
        $this->courses = $courseService;
    }

    function purchase(InitPaymentRequest $request)
    {
        $course = $this->courses->get($request->getCourseId());
        $this->authorize('buy', $course);

        if ($this->courses->attendanceStatus($course, $request->user())->hasAccess())
        {
            return \response()->json(['message' => 'You have already purchased this course or have an open payment for it']);
        }

        if (!$request->user()->can('buy', ))
        {
            return response()->json(['message' => 'You are not authorized to make this purchase'], 403);
        }

        if (!$course->canBePurchased())
            return response()->json(['message' => 'This course is not available for purchase']);

        $preview = $data['preview'] ?? false;

        if ($preview && $course->preview_units === 0)
            return response()->json(['message' => 'This course is not available for preview purchase'], 409);

        $externalPaymentId = $preview ? null : $this->createPayment();

        $payment = new CoursePurchase([
            'course_id' => $courseId,
            'price' => $course->price,
            'user_id' => $request->user()->id,
            'payment_external_id' => $externalPaymentId,
            'status' => $preview ? null : 'pending',
            'preview' => $preview
        ]);

        $payment->save();

        return [
            'id' => $payment->id,
            'redirect_url' => $preview ? null : $this->getPaymentRedirectUrl($payment, $request)
        ];
    }

    function checkPaymentStatus(AuthenticatedRequest $request)
    {
        // TODO I don't have an actual payment system so there's no need to check if payment is cancelled because
        //      it always successful, but that would be still good to implement
        //      UPD: I mean check itself not payments system ofc

        $paymentId = $request->payment;
        $payment = CoursePurchase::query()
            ->with([
                'course' => function(Builder $q) {
                    $q->select([
                        'name',
                    ]);
                }
            ])
            ->where('user_id', '=', Auth::id())
            ->findOrFail($paymentId);

        $status = $this->checkPayment($payment->payment_external_id);

        if ($payment->status !== $status)
            $payment->update([
                'status' => $status
            ]);

        return response()->json([
            'status' => $status,
            'redirect_url' => $status === 'pending' ? $this->getPaymentRedirectUrl($payment, $request) : null,
            'course' => [

            ]
        ], 400);
    }

    function getPaymentRedirectUrl(CoursePurchase $payment, Request $request) {

        // NOTE: Here we immediately return user to the payment check page
        // since i don't have a payment system
        return $request->getSchemeAndHttpHost() . '/payments/check/' . $payment->id;
    }
}
