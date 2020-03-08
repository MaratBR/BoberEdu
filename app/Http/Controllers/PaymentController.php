<?php

namespace App\Http\Controllers;

use App\Course;
use App\CoursePurchase;
use App\Http\Requests\AuthenticatedRequest;
use App\Http\Requests\InitPaymentRequest;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    function makePaymentSomehow(): string {
        return bin2hex(random_bytes(15));
    }

    function checkPaymentSomehow(string $id) {
        return true;
    }

    function initPayment(InitPaymentRequest $request)
    {
        $data = $request->validated();
        $courseId = $data['course_id'];
        $course = Course::getWithDetailsOrFail($courseId);
        if (!$request->user()->can('buy', $course))
        {
            return response()->json(['message' => 'You are not authorized to make this purchase'], 403);
        }

        if (!$course->canBePurchased())
            return response()->json(['message' => 'This course is not available for purchase']);

        $ip = $request->getClientIp();
        $ua = $request->header('User-Agent');

        if (gettype($ua) !== 'string')
            return response()->json(['message' => 'Invalid user agent'], 422);

        $preview = $data['preview'] ?? false;

        if ($preview && !$course->has_preview)
            return response()->json(['message' => 'This course is not available for preview purchase'], 409);

        $externalPaymentId = $preview ? null : $this->makePaymentSomehow();

        $payment = new CoursePurchase([
            'course_id' => $courseId,
            'price' => $course->price,
            'user_id' => $request->user()->id,
            'payment_external_id' => $externalPaymentId,
            'preview' => $preview
        ]);

        $payment->save();

        return [
            'id' => $payment->id,
            'redirect_url' => $request->getSchemeAndHttpHost() . '/payments/testing/PayFake?id=' . $payment->id
        ];
    }

    function confirmPayment(AuthenticatedRequest $request)
    {
        $paymentId = $request->payment;
        $payment = CoursePurchase::query()
            ->where('user_id', '=', Auth::id())
            ->findOrFail($paymentId);

        if (!$this->checkPaymentSomehow($payment->payment_external_id))
            return response()->json([
                'success' => false
            ]);
        return response()->json([
            'success' => true
        ]);
    }
}
