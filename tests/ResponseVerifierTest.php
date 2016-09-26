<?php

namespace Tests\Payment\EsunBank\VirtualAccount;

use Carbon\Carbon;
use VeryBuy\Payment\EsunBank\VirtualAccount\Response\ResponseVerifier;

class ResponseVerifierTest extends AbstractTestCase
{
    protected $responseStub;

    public function setUp()
    {
        parent::setUp();

        $this->responseStub = json_decode(
            '{"Data":"99990101,\u9280\u884c,43,88888888888888,1,99990101100704,999944012345601010111CR+00000000000117+00000602360210\uff21\uff34\uff2d\u8de8\u884c\u8f49888888888 00042822000029953323178888888B00901010111100704"}', true)['Data'];
        $this->responseStub = json_decode(
            '{"Data":"20160920,\u9280\u884c,1,92623000000001,1,20160920235725,053294000580501050921CR+00000000000100+00000131862730\u7db2\u8def\u975e\u7d04\u8f49\u5e33000000001 00002808000095697915305792623095601050920235725"}', true)['Data'];
    }

    public function testVerifierAfterParseAttributes()
    {
        $verifier = new ResponseVerifier($this->responseStub);

        $this->assertTrue(($verifier->getTradedAt() instanceof Carbon));
        $this->assertTrue(($verifier->getPaidAt() instanceof Carbon));
        $this->assertNotNull($verifier->getVirtualAccount());
        $this->assertTrue(is_numeric($verifier->getAmount()));
    }
}
