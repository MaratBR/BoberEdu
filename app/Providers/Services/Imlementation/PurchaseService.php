<?php


namespace App\Providers\Services\Implementation;


use App\CourseAttendance;
use App\Providers\Services\Abs\IExternalPaymentService;
use App\Providers\Services\Abs\IPurchasesService;
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

    function create(string $title, string $redirect, float $price, User $customer): Purchase
    {
        $externalPayment = $this->externalPaymentService->placePayment($price, $title, $redirect);

        /** @var Purchase $purchase */
        $purchase = Purchase::create([
            'external_redirect_url' => $externalPayment->redirect(),
            'external_id' => $externalPayment->id(),
            'status' => 'pending',
            'user_id' => $customer->id,
            'price' => $price
        ]);
        $purchase->refresh();
        return $purchase;
    }
}
