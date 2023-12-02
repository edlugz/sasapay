<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;

class Statement extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected $endPoint = 'transactions/';

    /**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * Statement constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->merchantCode = config('sasapay.merchant_code');
    }

    /**
     * fetch your transactions statement directly from our API.
     *
     * @param string merchantCode
     * @param string accountNumber
     */
    public function fetch($accountNumber)
    {
        $parameters = [
            'merchantCode'  => $this->merchantCode,
            'accountNumber' => $accountNumber,
        ];

        return $this->call($this->endPoint, ['query' => $parameters], 'GET');
    }
}
