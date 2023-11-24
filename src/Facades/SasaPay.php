<?php

namespace EdLugz\SasaPay\Facades;

use Illuminate\Support\Facades\Facade;

class SasaPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sasapay';
    }
}
