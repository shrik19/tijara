<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'user-cart-return','user-cart-notify','push_notification','checkout_push_notification','product_push_notification',
        'api/getPaymentMethods',
        'api/initiatePayment',
        'api/handleShopperRedirect',
        'api/submitAdditionalDetails'
    ];
}
