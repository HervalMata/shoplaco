<?php

return [
    'enviroment' => env('PAGSEGURO_ENV'),
    'email' => env('PAGSEGURO_EMAIL'),
    'token' => env('PAGSEGURO_TOKEN_SANDBOX'),

    'url_checkout_sandbox' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout',
    'url_checkout_production' => 'https://ws.pagseguro.uol.com.br/v2/checkout',
    'url_redirect_after_request' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=',
    'url_transparente_session_sandbox' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions',
    'url_transparente_session_production' => 'https://ws.pagseguro.uol.com.br/v2/sessions',
    'url_transparente_js_sandbox' => 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',
    'url_transparente_js_production' => 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js',
    'url_transparente_sandbox' => 'https://ws.sandbox.pagseguro.uol.com.br/v2/transactions',
    'url_transparente_production' => 'https://ws.pagseguro.uol.com.br/v2/transactions',
];
