<?php

namespace EdLugz\SasaPay;

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
    public function balance()
    {
        return new Balance();
    }

    /**
     * Initiate BusinessOnboarding.
     *
     * @return BusinessOnboarding
     */
    public function businessOnboarding()
    {
        return new BusinessOnboarding();
    }

    /**
     * Initiate Business Payment.
     *
     * @return BusinessPayment
     */
    public function businessPayment()
    {
        return new BusinessPayment();
    }

    /**
     * Initiate customer.
     *
     * @return Customer
     */
    public function customer()
    {
        return new Customer();
    }

    /**
     * Initiate Fund transaction.
     *
     * @return Fund
     */
    public function fund()
    {
        return new Fund();
    }

    /**
     * Initiate PersonalOnboarding.
     *
     * @return PersonalOnboarding
     */
    public function personalOnboarding()
    {
        return new PersonalOnboarding();
    }

    /**
     * Initiate SendMoney.
     *
     * @return SendMoney
     */
    public function sendMoney()
    {
        return new SendMoney();
    }

    /**
     * Initiate Statement.
     *
     * @return Statement
     */
    public function statement()
    {
        return new Statement();
    }

    /**
     * Initiate Supplementary.
     *
     * @return Supplementary
     */
    public function supplementary()
    {
        return new Supplementary();
    }

    /**
     * Initiate Transaction.
     *
     * @return Transaction
     */
    public function transaction()
    {
        return new Transaction();
    }

    /**
     * Initiate UtilityPayment.
     *
     * @return UtilityPayment
     */
    public function utilityPayment()
    {
        return new UtilityPayment();
    }
}
