<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;

class Balance extends SasaPayClient
{
    /**
     * check merchant balance end point on Sasapay API.
     *
     * @var string
     */
    protected string $checkEndPoint = 'customer-wallets/';

    /**
     * Check merchant balance.
     *
     * @param string $accountNumber
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    protected function check(string $accountNumber): mixed
    {
        $parameters = [
            'merchantCode'  => $this->merchantCode,
            'accountNumber' => $accountNumber,
            'countryCode'   => '254',
        ];

        return $this->call($this->checkEndPoint, ['json' => $parameters], 'POST');
    }
}
