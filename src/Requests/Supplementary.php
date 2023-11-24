<?php

namespace EdLugz\SasaPay\Requests;

use EdLugz\SasaPay\SasaPayClient;

class Supplementary extends SasaPayClient
{
    /**
     * channel code end point on Sasapay API.
     *
     * @var string
     */
    protected $channelEndPoint = 'channel-codes/';
	
    /**
     * countries end and sub regions end point on Sasapay API.
     *
     * @var string
     */
    protected $countryEndPoint = 'countries/';
	
    protected $subRegionEndPoint = 'countries/sub-regions/';
	
    /**
     * industries end and sub industries end point on Sasapay API.
     *
     * @var string
     */
    protected $industryEndPoint = 'industries/';
	
    protected $subIndustryEndPoint = 'sub-industries/';
	
    /**
     * business types end point on Sasapay API.
     *
     * @var string
     */
    protected $businessTypeEndPoint = 'business-types/';
	
    /**
     * accountProduct types end point on Sasapay API.
     *
     * @var string
     */
    protected $accountProductTypeEndPoint = 'products/';
	
    /**
     * Nearest SasaPay Agents end point on Sasapay API.
     *
     * @var string
     */
    protected $sasaPayAgentsEndPoint = 'nearest-agent/';
	
	/**
     * The merchant code assigned for the application on Sasapay API.
     *
     * @var string
     */
    protected $merchantCode;

    /**
     * Supplementary constructor.
     */
    public function __construct()
    {
        parent::__construct();
		
        $this->merchantCode = config('sasapay.merchant_code');     
		
    }

    /**
     * get channel codes		
     */
    protected function channelCodes()
    {
        return $this->call($this->channelEndPoint, [], 'GET');
    }
	
    /**
     * get countries		
     */
    protected function countries()
    {
        return $this->call($this->countryEndPoint, [], 'GET');
    }
	
    /**
     * get sub regions		
     */
    protected function subRegions()
    {
        return $this->call($this->subRegionEndPoint, [], 'GET');
    }
	
    /**
     * get industries		
     */
    protected function industries()
    {
        return $this->call($this->industryEndPoint, [], 'GET');
    }
	
    /**
     * get sub regions		
     */
    protected function subIndustries()
    {
        return $this->call($this->subIndustryEndPoint, [], 'GET');
    }
	
    /**
     * get business types	
     */
    protected function businessTypes()
    {
        return $this->call($this->businessTypeEndPoint, [], 'GET');
    }
	
    /**
     * get account product types	
     */
    protected function accountProductTypes()
    {
        return $this->call($this->accountProductTypeEndPoint, [], 'GET');
    }
	
    /**
     * get nearest sasapay agent locations	
     */
    protected function accountProductTypes()
    {
        return $this->call($this->sasaPayAgentsEndPoint, [], 'GET');
    }
	
}