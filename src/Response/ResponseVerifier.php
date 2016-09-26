<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Response;

use Carbon\Carbon;

class ResponseVerifier implements ParseInterface
{
    use ResponseParseTrait;

    /**
     * @param string $encrypted
     */
    public function __construct($encrypted = null)
    {
        if (! is_null($encrypted)) {
            $this->parse = static::parse($encrypted);
        }
    }

    /**
     * @return mixed string|null
     */
    public function getPlatform()
    {
        return $this->parse->get('platform', null);
    }

    /**
     * @return mixed Carbon|null
     */
    public function getTradedAt()
    {
        return $this->parse->get('traded_at', null);
    }

    /**
     * @return mixed Carbon|null
     */
    public function getPaidAt()
    {
        return $this->parse->get('paid_at', null);
    }

    /**
     * @return mixed string|null
     */
    public function getVirtualAccount()
    {
        return $this->parse->get('vaccount', null);
    }

    /**
     * @return mixed int|null
     */
    public function getAmount()
    {
        return $this->parse->get('amount', null);
    }
}
