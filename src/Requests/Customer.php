<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;

class Customer extends SasaPayClient
{
    /**
     * customers end point on Sasapay API.
     *
     * @var string
     */
    protected string $customersEndPoint = 'customers/';

    protected string $detailEndPoint = 'customer-details/';


    /**
     * Retrieve the List of all your customers.
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function getCustomers(): mixed
    {
        $parameters = [
            'merchant_code' => $this->merchantCode,
        ];

        return $this->call($this->customersEndPoint, ['query' => $parameters], 'GET');
    }

    /**
     * Retrieve details of an individual customer.
     *
     * @param $mobileNumber
     * @return mixed
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function customerDetails($mobileNumber): mixed
    {
        $parameters = [
            'merchantCode'  => $this->merchantCode,
            'accountNumber' => $mobileNumber,
            'countryCode'   => '254',
        ];

        return $this->call($this->detailEndPoint, ['json' => $parameters]);
    }
}
