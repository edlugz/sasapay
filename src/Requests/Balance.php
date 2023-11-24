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
    protected $checkEndPoint = 'merchant-balances/';
	
	/**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * Balance constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->merchantCode = config('sasapay.merchant_code');     
		
    }

    /**
     * Check merchant balance
     
      	@param string merchantCode
		
     */
    protected function check()
    {
        $parameters = [
            "merchantCode" => $this->merchantCode
        ];

        return $this->call($this->checkEndPoint, ['json' => $parameters], 'GET');
    }
	
}