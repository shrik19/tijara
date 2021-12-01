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
        'api/submitAdditionalDetails',
        'swish-ipn-callback',
        'getSwishPaymentMethods',
        'swishInitiatePayment',
        'checkoutSubmitAdditionalDetails',
        'checkout-swish-ipn',
		'admin/ck_upload',
        'swish-ipn-url','checkout-swish-number-callback'
    ];
}
