<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Generator;

use Carbon\Carbon;

class SingleVerifyCodeGenerator extends VerifyCodeGeneratorContract implements VerifyInterface
{
    use VerifyCodeRule;

    const VERIFY_CODE_BASE = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    const VERIFY_CODE_AMOUNT = [1, 2, 3, 4, 5, 6, 7, 8];

    /**
     * @param string $account
     * @param string $number
     *
     * @return string virtual account
     */
    public function buildWithBase($account, $number)
    {
        $account = $account.$number;

        return $account.$this->genVerifyCode(static::VERIFY_CODE_BASE, $account);
    }

    /**
     * @param string    $account
     * @param string    $number
     * @param type      $amount
     *
     * @return string virtual account
     */
    public function buildWithAmount($account, $number, $amount)
    {
        $account = $account.$number;

        $v1 = $this->genVerifyCode(static::VERIFY_CODE_BASE, $account);
        $v2 = $this->genVerifyCode(static::VERIFY_CODE_AMOUNT, $amount);

        return $account.(($v1 + $v2) % 10);
    }

    /**
     * @param string    $account
     * @param string    $number
     * @param type      $amount
     * @param timestamp $date
     *
     * @return string virtual account
     */
    public function buildWithAmountAndDate($account, $number, $amount, $date)
    {
        $number = Carbon::createFromTimestamp($date)->format('md').$number;

        return $this->buildWithAmount($account, $number, $amount);
    }

    /**
     * @param string    $account
     * @param string    $number
     * @param type      $amount
     * @param timestamp $datetime
     *
     * @return string virtual account
     */
    public function buildWithAmountAndDateTime($account, $number, $amount, $datetime)
    {
        $dt = Carbon::createFromTimestamp($datetime)->addDay();

        $number = $dt->dayOfYear.$dt->format('H').$number;

        return $this->buildWithAmount($account, $number, $amount);
    }
}
