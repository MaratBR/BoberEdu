<?php


namespace App\Services\Implementation;


use App\Course;
use App\Exceptions\Payment\PaymentFailed;
use App\Exceptions\ThrowUtils;
use App\Payment;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\IPaymentsService;
use App\Services\Abs\Payments\IPaymentGatewayHandler;
use App\Services\Implementation\Payments\DummyGatewayHandler;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentsService implements IPaymentsService
{
    use ThrowUtils;

    private $enrollments;

    private $gatewayAliases = [
        'test' => 'dummy',
    ];

    /**
     * @var string[]
     */
    private $gateawayHandlers = [
        'dummy' => DummyGatewayHandler::class
    ];

    public function __construct(IEnrollmentService $enrollmentService)
    {
        $this->enrollments = $enrollmentService;
    }


    function createPayment(Course $course, Request $request, User $user, string $gateawayName, array $data): Payment
    {
        // NOTE: Development dummy implementation
        // TODO integrated with Paypal or Sberbank if I have time

        $title = "Course \"{$course->name}\" @ Bober.Edu";
        $price = $course->price;
        $gateawayName = $this->getGatewayName($gateawayName);
        $handler = $this->gateawayHandlers[$gateawayName];

        /** @var IPaymentGatewayHandler $handler */
        $handler = new $handler();
        assert($handler instanceof IPaymentGatewayHandler, "Invalid payment gateaway handler");

        // Payment

        $id = Str::uuid()->toString();
        $response = $handler->request($title, $price, $id, $data);
        $this->throwErrorIf(400, "Payment cancelled: {$response->getMessage()}", $response->isCancelled());
        $redirect = null;

        if ($response->isRedirect()) {
            $status = Payment::STATUS_PENDING;
            $redirect = $response->getRedirectUrl();
        } elseif ($response->isCancelled()) {
            $status = Payment::STATUS_CANCELLED;
        } elseif ($response->isSuccessful()) {
            $status = Payment::STATUS_SUCCESSFUL;
        } else {
            throw new PaymentFailed("Payment failed to process: {$response->getMessage()}");
        }


        $payment = new Payment([
            'id' => $id,
            'user_id' => $user->id,
            'title' => $title,
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'gateaway_name' => $gateawayName,
            'amount' => $price,
            'redirect_url' => $redirect,
            'uid' => $response->getTransactionReference(),
            'status' => $status
        ]);

        $payment->save();
        $payment->refresh();

        return $payment;
    }

    function getGatewayName(string $name): string
    {
        $name = strtolower($name);
        if (array_key_exists($name, $this->gatewayAliases)) {
            $name = $this->gatewayAliases[$name];
        }

        return $name;
    }

    function hasGateaway(string $gateaway)
    {
        $gateaway = $this->getGatewayName($gateaway);
        return array_key_exists($gateaway, $this->gateawayHandlers);
    }

    function externalPaymentStatus(Payment $payment): ?bool
    {
        $gateway = $this->getGatewayName($payment->gateaway_name);
        /** @var IPaymentGatewayHandler $gateway */
        $gateway = $this->gateawayHandlers[$gateway];
        return $gateway->externalStatus($payment->uid);
    }
}
