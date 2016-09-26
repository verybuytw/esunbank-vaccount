<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Generator;

class NoneVerifyCodeGenerator extends VerifyCodeGeneratorContract
{
    /**
     * @param string $account
     * @param string $number
     *
     * @return string virtual account
     */
    public function buildWithBase($account, $number)
    {
        return $account.$number;
    }
}
