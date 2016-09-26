<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Generator;

interface VerifyInterface
{
    /**
     * @param string    $account   Company id from EsunBank.
     * @param string    $number
     * 
     * @return string virtual account
     */
    public function buildWithBase($account, $number);

    /**
     * @param string    $account    Company id from EsunBank.
     * @param string    $number
     * @param int       $amount
     *
     * @return string virtual account with amount
     */
    public function buildWithAmount($account, $number, $amount);

    /**
     * @param string    $account    Company id from EsunBank.
     * @param string    $number
     * @param int       $amount
     * @param date      $date
     * 
     * @return string virtual account with amount and date
     */
    public function buildWithAmountAndDate($account, $number, $amount, $date);

    /**
     * @param string    $account    Company id from EsunBank.
     * @param string    $number
     * @param int       $amount
     * @param datetime  $datetime
     * 
     * @return string verify code with amount and datetime
     */
    public function buildWithAmountAndDateTime($account, $number, $amount, $datetime);
}
