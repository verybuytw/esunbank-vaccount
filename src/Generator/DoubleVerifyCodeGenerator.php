<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Generator;

use Carbon\Carbon;

class DoubleVerifyCodeGenerator extends VerifyCodeGeneratorContract implements VerifyInterface
{
    use VerifyCodeRule;

    const VERIFY_CODE_BASE_PART1 = [1, 3, 7];
    const VERIFY_CODE_BASE_PART2 = [3, 7, 9];
    const VERIFY_CODE_AMOUNT_PART1 = [1, 3, 7];
    const VERIFY_CODE_AMOUNT_PART2 = [3, 7, 9];

    /**
     * @param string $account
     * @param string $number
     *
     * @return string virtual account
     */
    public function buildWithBase($account, $number)
    {
        $account = $account.$number;

        $p1 = $this->genVerifyCode(static::VERIFY_CODE_BASE_PART1, $account);
        $p2 = $this->genVerifyCode(static::VERIFY_CODE_BASE_PART2, $account);

        return $account.$p1.$p2;
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

        $a1 = $this->genVerifyCode(static::VERIFY_CODE_BASE_PART1, $account);
        $a2 = $this->genVerifyCode(static::VERIFY_CODE_BASE_PART2, $account);

        $m1 = $this->genVerifyCode(static::VERIFY_CODE_AMOUNT_PART1, $amount);
        $m2 = $this->genVerifyCode(static::VERIFY_CODE_AMOUNT_PART2, $amount);

        $p1 = static::getLastNumber(($a1 + $m1));

        $p2 = static::getLastNumber(($a2 + $m2));

        return $account.$p1.$p2;
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
        return $this->buildWithAmountAndDate($account, $number, $amount, $datetime);
    }
}
