<?php

namespace EdLugz\SasaPay;

use EdLugz\SasaPay\Helpers\SasaPayHelper;
use EdLugz\SasaPay\Requests\Balance;
use EdLugz\SasaPay\Requests\BusinessOnboarding;
use EdLugz\SasaPay\Requests\BusinessPayment;
use EdLugz\SasaPay\Requests\Customer;
use EdLugz\SasaPay\Requests\Fund;
use EdLugz\SasaPay\Requests\PersonalOnboarding;
use EdLugz\SasaPay\Requests\SendMoney;
use EdLugz\SasaPay\Requests\Statement;
use EdLugz\SasaPay\Requests\Supplementary;
use EdLugz\SasaPay\Requests\Transaction;
use EdLugz\SasaPay\Requests\UtilityPayment;

class SasaPay
{
    /**
     * Initiate a balance enquiry.
     *
     * @return Balance
     */
    public function balance(): Balance
    {
        return new Balance();
    }

    /**
     * Initiate BusinessOnboarding.
     *
     * @return BusinessOnboarding
     */
    public function businessOnboarding(): BusinessOnboarding
    {
        return new BusinessOnboarding();
    }

    /**
     * Initiate Business Payment.
     *
     * @return BusinessPayment
     */
    public function businessPayment(): BusinessPayment
    {
        return new BusinessPayment();
    }

    /**
     * Initiate customer.
     *
     * @return Customer
     */
    public function customer(): Customer
    {
        return new Customer();
    }

    /**
     * Initiate Fund transaction.
     *
     * @return Fund
     */
    public function fund(): Fund
    {
        return new Fund();
    }

    /**
     * Initiate PersonalOnboarding.
     *
     * @return PersonalOnboarding
     */
    public function personalOnboarding(): PersonalOnboarding
    {
        return new PersonalOnboarding();
    }

    /**
     * Initiate SendMoney.
     *
     * @return SendMoney
     */
    public function sendMoney(): SendMoney
    {
        return new SendMoney();
    }

    /**
     * Initiate Statement.
     *
     * @return Statement
     */
    public function statement(): Statement
    {
        return new Statement();
    }

    /**
     * Initiate Supplementary.
     *
     * @return Supplementary
     */
    public function supplementary(): Supplementary
    {
        return new Supplementary();
    }

    /**
     * Initiate Transaction.
     *
     * @return Transaction
     */
    public function transaction(): Transaction
    {
        return new Transaction();
    }

    /**
     * Initiate UtilityPayment.
     *
     * @return UtilityPayment
     */
    public function utilityPayment(): UtilityPayment
    {
        return new UtilityPayment();
    }

    public function helper(): SasaPayHelper
    {
        return new SasaPayHelper();
    }
}
