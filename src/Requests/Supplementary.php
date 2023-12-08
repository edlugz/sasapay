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
    protected string $channelEndPoint = 'channel-codes/';

    /**
     * countries end and sub regions end point on Sasapay API.
     *
     * @var string
     */
    protected string $countryEndPoint = 'countries/';

    protected string $subRegionEndPoint = 'countries/sub-regions/';

    /**
     * industries end and sub industries end point on Sasapay API.
     *
     * @var string
     */
    protected string $industryEndPoint = 'industries/';

    protected string $subIndustryEndPoint = 'sub-industries/';

    /**
     * business types end point on Sasapay API.
     *
     * @var string
     */
    protected string $businessTypeEndPoint = 'business-types/';

    /**
     * accountProduct types end point on Sasapay API.
     *
     * @var string
     */
    protected string $accountProductTypeEndPoint = 'products/';

    /**
     * Nearest SasaPay Agents end point on Sasapay API.
     *
     * @var string
     */
    protected string $sasaPayAgentsEndPoint = 'nearest-agent/';

    /**
     * get channel codes.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function channelCodes()
    {
        return $this->call($this->channelEndPoint, [], 'GET');
    }

    /**
     * get countries.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function countries()
    {
        return $this->call($this->countryEndPoint, [], 'GET');
    }

    /**
     * get sub regions.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function subRegions()
    {
        return $this->call($this->subRegionEndPoint, [], 'GET');
    }

    /**
     * get industries.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function industries()
    {
        return $this->call($this->industryEndPoint, [], 'GET');
    }

    /**
     * get sub regions.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function subIndustries()
    {
        return $this->call($this->subIndustryEndPoint, [], 'GET');
    }

    /**
     * get business types.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function businessTypes()
    {
        return $this->call($this->businessTypeEndPoint, [], 'GET');
    }

    /**
     * get account product types.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    public function accountProductTypes()
    {
        return $this->call($this->accountProductTypeEndPoint, [], 'GET');
    }

    /**
     * get nearest sasapay agent locations.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     *
     * @return mixed
     */
    public function agentLocations(): mixed
    {
        return $this->call($this->sasaPayAgentsEndPoint, [], 'GET');
    }

    /**
     * get channel code from mobile number
     *
	 * @param string $mobileNumber - 0XXXXXXXXX
	 *
     * @return string
     */
    public function getChannel(): string
    {
        $safaricom = '/(?:0)?((?:(?:7(?:(?:[01249][0-9])|(?:5[789])|(?:6[89])))|(?:1(?:[1][0-5])))[0-9]{6})$/';
		$airtel = '/(?:0)?((?:(?:7(?:(?:3[0-9])|(?:5[0-6])|(8[5-9])))|(?:1(?:[0][0-2])))[0-9]{6})$/';
		$telkom = '/(?:0)?(77[0-9][0-9]{6})/';
		if(preg_match($safaricom, $str)){
			return '63902';
		}
		if(preg_match($airtel, $str)){
			return '63903';
		}
		if(preg_match($telkom, $str)){
			return '63907';
		}
    }
}
