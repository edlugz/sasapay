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
    protected function channelCodes()
    {
        return $this->call($this->channelEndPoint, [], 'GET');
    }

    /**
     * get countries.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function countries()
    {
        return $this->call($this->countryEndPoint, [], 'GET');
    }

    /**
     * get sub regions.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function subRegions()
    {
        return $this->call($this->subRegionEndPoint, [], 'GET');
    }

    /**
     * get industries.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function industries()
    {
        return $this->call($this->industryEndPoint, [], 'GET');
    }

    /**
     * get sub regions.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function subIndustries()
    {
        return $this->call($this->subIndustryEndPoint, [], 'GET');
    }

    /**
     * get business types.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function businessTypes()
    {
        return $this->call($this->businessTypeEndPoint, [], 'GET');
    }

    /**
     * get account product types.
     *
     * @throws \EdLugz\SasaPay\Exceptions\SasaPayRequestException
     */
    protected function accountProductTypes()
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
    protected function agentLocations(): mixed
    {
        return $this->call($this->sasaPayAgentsEndPoint, [], 'GET');
    }
}
