<?php


namespace App\Services\Implementation;


use App\Course;
use App\Enrollment;
use App\Exceptions\ThrowUtils;
use App\Payment;
use App\Services\Abs\IEnrollmentService;
use App\Services\Abs\IPaymentsService;
use App\Services\Abs\Payments\IPaymentGatewayHandler;
use App\Services\Implementation\Payments\DummyGatewayHandler;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Lanin\Laravel\ApiExceptions\ConflictApiException;
use Omnipay\Common\CreditCard;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Omnipay;
use Psy\Util\Json;

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
        $title = "Course \"{$course->name}\" @ Bober.Edu";
        $price = $course->price;

        if (array_key_exists($gateawayName, $this->gatewayAliases))
        {
            $gateawayName = $this->gatewayAliases[$gateawayName];
        }

        $handler = $this->gateawayHandlers[$gateawayName];
        /** @var IPaymentGatewayHandler $handler */
        $handler = new $handler();
        assert($handler instanceof IPaymentGatewayHandler, "Invalid payment gateaway handler");

        $response = $handler->request($title, $price, $data);

        $this->throwErrorIf(400, "Payment cancelled: {$response->getMessage()}", $response->isCancelled());
        $paymentExpiration = null;
        $redirect = null;

        if ($response->isRedirect())
        {
            $redirect = $response->getRedirectUrl();
        }

        $payment = new Payment([
            'user_id' => $user->id,
            'title' => $title,
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'expires_at' => $paymentExpiration,
            'gateaway_name' => $gateawayName,
            'redirect_url' => $redirect,
            'uid' => $response->getTransactionReference(),
            'amount' => $price,
            'is_successful' => $response->isSuccessful(),
            'is_pending' => $response->isRedirect()
        ]);

        $payment->save();
        $payment->refresh();

        return $payment;
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
