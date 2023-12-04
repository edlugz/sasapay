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
    protected string $checkEndPoint = 'transactions/status/';

    /**
     * verify transaction end point on Sasapay API.
     *
     * @var string
     */
    protected string $verifyEndPoint = 'transactions/verify/';

    /**
     * Check Transaction Status.
     *
     * @param $checkoutRequestId
     * @param $merchantTransactionReference
     * @param $transactionCode
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function check($checkoutRequestId, $merchantTransactionReference, $transactionCode): mixed
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
     * @param $transactionCode
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function verify($transactionCode): mixed
    {
        $parameters = [
            'merchantCode'    => $this->merchantCode,
            'transactionCode' => $transactionCode,
        ];

        return $this->call($this->verifyEndPoint, ['json' => $parameters]);
    }
}
