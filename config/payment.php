<?php

return [
    /**
     *
     * Midtrans as default payment gateway
     * Available options: midtrans, tripay, xendit, doku
     **/
    'gateway' => env('PAYMENT_GATEWAY', 'midtrans'),
];
