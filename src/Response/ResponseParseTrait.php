<?php

namespace VeryBuy\Payment\EsunBank\VirtualAccount\Response;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait ResponseParseTrait
{
    /**
     * @var mixed array|null
     */
    protected $parse;

    /**
     * @param string $parse
     *
     * @return Collection
     */
    public function parse($parse)
    {
        $parse = explode(',', $parse);

        return Collection::make([
            'traded_at' => Carbon::createFromTimestamp(strtotime($parse[0])),
            'platform' => $parse[1],
            'serial' => $parse[2],
            'vaccount' => $parse[3],
            'amount' => $parse[4],
            'paid_at' => Carbon::createFromTimestamp(strtotime($parse[5])),
            'origin' => static::subParse($parse[6]),
        ]);
    }

    /**
     * @param string $parse
     *
     * @return Collection
     */
    protected function subParse($encrypted)
    {
        return Collection::make([
            'vaccount' => mb_substr($encrypted, 0, 13),
            'traded_at' => mb_substr($encrypted, 13, 8),
            'action' => mb_substr($encrypted, 21, 2),
            'action_type' => mb_substr($encrypted, 23, 1),
            'amount' => mb_substr($encrypted, 24, 12),
            'fee' => mb_substr($encrypted, 36, 2),
            'deposit_type' => mb_substr($encrypted, 38, 1),
            'deposit' => mb_substr($encrypted, 39, 12),
            'traded_platform' => mb_substr($encrypted, 51, 1),
            'saved' => mb_substr($encrypted, 52, 1),
            'subject' => mb_substr($encrypted, 53, 6),
            'comment' => mb_substr($encrypted, 59, 10),
            'serial' => mb_substr($encrypted, 69, 5),
            'bank_id' => mb_substr($encrypted, 74, 3),
            'paid' => [
                'account' => mb_substr($encrypted, 77, 16),
                'bank_id' => mb_substr($encrypted, 98, 4),
            ],
            'company_id' => mb_substr($encrypted, 93, 5),
            'traded_date' => mb_substr($encrypted, 102, 8),
            'traded_time' => mb_substr($encrypted, 110, 6),
        ]);
    }
}
