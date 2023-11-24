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
    protected $customersEndPoint = 'customers/';
	
    protected $detailEndPoint = 'customer-details/';

    /**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->merchantCode = config('sasapay.merchant_code');       
    }

    /**
     * Retrieve the List of all your customers
     
      	@param string merchantCode
		
     */
    public function getCustomers()
    {
        $parameters = [
            "merchant_code" => $this->merchantCode
        ];

        return $this->call($this->customersEndPoint, ['query' => $parameters], 'GET');
    }	
	
    /**
     * Retrieve details of an individual customer
     
      	@param string merchantCode
      	@param string accountNumber
		
     */
    public function customerDetails($accountNumber)
    {
        $parameters = [
            "merchantCode" => $this->merchantCode,
            "accountNumber" => $accountNumber,
            "countryCode" => "254"
        ];

        return $this->call($this->detailEndPoint, ['json' => $parameters]);
    }	
	
}