<?php

namespace Tests\Payment\EsunBank\VirtualAccount;

use VeryBuy\Payment\EsunBank\VirtualAccount\VerifyType;
use VeryBuy\Payment\EsunBank\VirtualAccount\VirtualAccountBuilder;

class VirtualAccountBuilderTest extends AbstractTestCase
{
    public function testBuilderWithoutVerifyCode()
    {
        $builder = new VirtualAccountBuilder('99123', [
            'type' => VerifyType::NONE_BASE,
            'length' => 14,
            'number' => '393988912',  // (14碼)自訂碼 length:9 ; (13碼)自訂碼 length:8
        ]);

        $this->assertEquals('99123393988912', $builder->make());
    }

    public function testBuilderWithSingleGenerator()
    {
        $builder = new VirtualAccountBuilder('99123', [
            'type' => VerifyType::SINGLE_BASE,
            'length' => 13,
            'number' => '3939889',  // (14碼)自訂碼 length:8 ; (13碼)自訂碼 length:7
        ]);

        $this->assertEquals('9912339398893', $builder->make());

        $builder = new VirtualAccountBuilder('99123', [
            'type' => VerifyType::SINGLE_AMOUNT,
            'length' => 13,
            'number' => '3939889',  // (14碼)自訂碼 length:8 ; (13碼)自訂碼 length:7
            'amount' => 1500,       // 金額     max length:8
        ]);

        $this->assertEquals('9912339398892', $builder->make());

        $builder = new VirtualAccountBuilder('99123', [
            'type' => VerifyType::SINGLE_AMOUNT_DATE,
            'length' => 13,
            'number' => '001',  // (14碼)自訂碼 length:4 ; (13碼)自訂碼 length:3
            'amount' => 1500,   // 金額     max length:8
            'expired_at' => strtotime('2016-01-19'),
        ]);

        $this->assertEquals('9912301190015', $builder->make());

        $builder = new VirtualAccountBuilder('99123', [
            'type' => VerifyType::SINGLE_AMOUNT_DATETIME,
            'length' => 13,
            'number' => '89',   // (14碼)自訂碼 length:3 ; (13碼)自訂碼 length:2
            'amount' => 1500,   // 金額     max length:8
            'expired_at' => strtotime('2016-07-31 10:00'),
        ]);

        $this->assertEquals('9912321310891', $builder->make());
    }

    public function testBuilderWithDoubleGenerator()
    {
        $builder = new VirtualAccountBuilder('99551', [
            'type' => VerifyType::DOUBLE_BASE,
            'length' => 13,
            'number' => '000001',   // (14碼)自訂碼 length:7 ; (13碼)自訂碼 length:6
        ]);

        $this->assertEquals('9955100000186', $builder->make());

        $builder = new VirtualAccountBuilder('99551', [
            'type' => VerifyType::DOUBLE_AMOUNT,
            'length' => 13,
            'number' => '000001',  // (14碼)自訂碼 length:7 ; (13碼)自訂碼 length:6
            'amount' => 1500,
        ]);

        $this->assertEquals('9955100000144', $builder->make());

        $builder = new VirtualAccountBuilder('99551', [
            'type' => VerifyType::DOUBLE_AMOUNT_DATE,
            'length' => 13,
            'number' => '01',   // (14碼)自訂碼 length:3 ; (13碼)自訂碼 length:2
            'amount' => 1500,   // 金額     max length:8
            'expired_at' => strtotime('2016-01-19'),
        ]);

        $this->assertEquals('9955101190115', $builder->make());
    }
}
