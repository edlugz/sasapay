<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Client Id
   |--------------------------------------------------------------------------
   |
   | This value is the consumer key provided for your developer application.
   | The package needs this to make requests to the SasaPay APIs.
   |
   */

    'client_id' => env('SASAPAY_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Client Secret
    |--------------------------------------------------------------------------
    |
    | This value is the consumer secret provided for your developer application.
    | The package needs this to make requests to the SasaPay APIs.
    |
    */

    'client_secret' => env('SASAPAY_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Merchant Code
    |--------------------------------------------------------------------------
    |
    | This value is the consumer secret provided for your developer application.
    | The package needs this to make requests to the SasaPay APIs.
    |
    */

    'merchant_code' => env('SASAPAY_MERCHANT_CODE', ''),

    /*
    |--------------------------------------------------------------------------
    | Package Mode
    |--------------------------------------------------------------------------
    |
    | This value sets the mode at which you are using the package. Acceptable
    | values are sandbox or live
    |
    */

    'mode' => 'live',

    /*
    |--------------------------------------------------------------------------
    | Onboarding URLs
    |--------------------------------------------------------------------------
    |
    | Here you can set the onboarding URLs that will handle the results from each of the
    | APIs from Sasapay
    |
    */

    'onboarding_result_url' => [
        'personal' => env('SASAPAY_PERSONAL_ONBOARDING_RESULT_URL', ''),
        'business' => env('SASAPAY_BUSINESS_ONBOARDING_RESULT_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Result URLs
    |--------------------------------------------------------------------------
    |
    | Here you can set the URLs that will handle the results from each of the
    | APIs from Sasapay
    |
    */

    'result_url' => [
        'funding'          => env('SASAPAY_FUNDING_RESULT_URL', ''),
        'send_money'       => env('SASAPAY_SEND_MONEY_RESULT_URL', ''),
        'business_payment' => env('SASAPAY_BUSINESS_PAYMENT_RESULT_URL', ''),
        'utility_payment'  => env('SASAPAY_UTILITY_PAYMENT_RESULT_URL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | LOGS
    |--------------------------------------------------------------------------
    |
    | Here you can set your logging requirements. If enabled a new file will
    | will be created in the logs folder and will record all requests
    | and responses to the Sasapay APIs. You can use the
    | the Monolog debug levels.
    |
    */

    'logs' => [
        'enabled' => true,
        'level'   => 'DEBUG',
    ],

];
