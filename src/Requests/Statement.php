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
    protected string $endPoint = 'transactions/';

    /**
     * fetch your transactions statement directly from our API.
     *
     * @param $accountNumber
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function fetch($accountNumber): mixed
    {
        $parameters = [
            'merchantCode'  => $this->merchantCode,
            'accountNumber' => $accountNumber,
        ];

        return $this->call($this->endPoint, ['query' => $parameters], 'GET');
    }
}
