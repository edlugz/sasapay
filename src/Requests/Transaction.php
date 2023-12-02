<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;

class Transaction extends SasaPayClient
{
    /**
     * check transaction status end point on Sasapay API.
     *
     * @var string
     */
    protected $checkEndPoint = 'transactions/status/';

    /**
     * verify transaction end point on Sasapay API.
     *
     * @var string
     */
    protected $verifyEndPoint = 'transactions/verify/';

    /**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * Transaction constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->merchantCode = config('sasapay.merchant_code');
    }

    /**
     * Check Transaction Status.
     *
     * @param string merchantCode
     * @param string checkoutRequestId
     * @param string merchantTransactionReference
     * @param string transactionCode
     */
    protected function check($checkoutRequestId, $merchantTransactionReference, $transactionCode)
    {
        $parameters = [
            'merchantCode'                 => $this->merchantCode,
            'checkoutRequestId'            => $checkoutRequestId,
            'merchantTransactionReference' => $merchantTransactionReference,
            'transactionCode'              => $transactionCode,
        ];

        return $this->call($this->checkEndPoint, ['json' => $parameters]);
    }

    /**
     * fetch your transactions statement directly from our API.
     *
     * @param string merchantCode
     * @param string transactionCode
     */
    protected function verify($transactionCode)
    {
        $parameters = [
            'merchantCode'    => $this->merchantCode,
            'transactionCode' => $transactionCode,
        ];

        return $this->call($this->verifyEndPoint, ['json' => $parameters]);
    }
}
