<?php

return [

    /*
   |--------------------------------------------------------------------------
   | Client Id
   |--------------------------------------------------------------------------
   |
   | This value is the consumer key provided for your developer application.
   | The package needs this to make requests to the Safricom APIs.
   |
   */

    'client_id' => env('SASAPAY_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Client Secret
    |--------------------------------------------------------------------------
    |
    | This value is the consumer secret provided for your developer application.
    | The package needs this to make requests to the Safricom APIs.
    |
    */

    'client_secret' => env('SASAPAY_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Merchant Code
    |--------------------------------------------------------------------------
    |
    | This value is the consumer secret provided for your developer application.
    | The package needs this to make requests to the Safricom APIs.
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
    | If you will be using the C2B API you can set the URLs that will handle the
    | validation and confirmation here. This will enable you to run the
    | artisan command to automatically register them. You can use a route name or
    | specific URL since we can not use the route() helper here
    |
    */

    'onboarding_result_url' => [
        'personal' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31',
        'business' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31'
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
        'funding' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31',
        'send_money' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31',
        'business_payment' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31',
        'utility_payment' => 'https://webhook.site/d434ef03-08cd-4cb6-aa9c-a8c9d08ccb31'
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
        'level' => 'DEBUG',
    ],

];