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
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
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
