<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount;

/**
 * Note: can not use zero to be value.
 */
interface VerifyType
{
    const NONE_BASE = 1;
    const SINGLE_BASE = 11;
    const SINGLE_AMOUNT = 12;
    const SINGLE_AMOUNT_DATE = 13;
    const SINGLE_AMOUNT_DATETIME = 14;
    const DOUBLE_BASE = 21;
    const DOUBLE_AMOUNT = 22;
    const DOUBLE_AMOUNT_DATE = 23;
}
