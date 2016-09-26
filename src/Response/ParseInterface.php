<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Response;

interface ParseInterface
{
    /**
     * @param array $parse
     */
    public function parse($parse);
}
