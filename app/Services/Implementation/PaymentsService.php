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
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentsService implements IPaymentsService
{
    use ThrowUtils;
    private $enrollments;

    private $gatewayAliases = [
        'dummy' => 'Dummy',
    ];

    /**
     * @var string[]
     */
    private $gateawayHandlers = [
        'Dummy' => DummyGatewayHandler::class
    ];

    public function __construct(IEnrollmentService $enrollmentService)
    {
        $this->enrollments = $enrollmentService;
    }


    function createPayment(Course $course, Request $request, User $user, string $gateawayName, array $data): Payment
    {
        // NOTE: Development dummy implementation
        // TODO integrated with Paypal or Sberbank if I have time


        $id = Str::uuid()->toString();
        $title = "Course \"{$course->name}\" @ Bober.Edu";
        $price = $course->price;
        $gateawayName = $this->getGatewayName($gateawayName);
        $handler = $this->gateawayHandlers[$gateawayName];

        /** @var IPaymentGatewayHandler $handler */
        $handler = new $handler();
        assert($handler instanceof IPaymentGatewayHandler, "Invalid payment gateaway handler");

        // Payment

        $response = $handler->request($title, $price, $id, $data);
        $this->throwErrorIf(400, "Payment cancelled: {$response->getMessage()}", $response->isCancelled());
        $paymentExpiration = null;
        $redirect = null;

        if ($response->isRedirect())
        {
            $status = Payment::STATUS_PENDING;
            $redirect = $response->getRedirectUrl();
        }
        elseif ($response->isCancelled())
        {
            $status = Payment::STATUS_CANCELLED;
        }
        elseif ($response->isSuccessful())
        {
            $status = Payment::STATUS_SUCCESSFUL;
        }
        else
        {
            throw new PaymentFailed("Payment failed to process: {$response->getMessage()}");
        }

        $payment = new Payment([
            'id' => $id,
            'user_id' => $user->id,
            'title' => $title,
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'expires_at' => $paymentExpiration,
            'gateaway_name' => $gateawayName,
            'redirect_url' => $redirect,
            'uid' => $response->getTransactionReference(),
            'amount' => $price,
            'status' => $status
        ]);

        $payment->save();
        $payment->refresh();

        return $payment;
    }

    function getGatewayName(string $name): string
    {
        if (array_key_exists($name, $this->gatewayAliases))
        {
            $name = $this->gatewayAliases[$name];
        }

        return $name;
    }

    function hasGateaway(string $gateaway)
    {
        if (array_key_exists($gateaway, $this->gatewayAliases))
        {
            $gateaway = $this->gatewayAliases[$gateaway];
        }

        return array_key_exists($gateaway, $this->gateawayHandlers);
    }
}
