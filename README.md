Installation
-------------

```shell
$    composer require vb-payment/esunbank-vaccount
```

### Use VirtualAccountBuilder generate form for esunbank virtual account


```php
<?php

    use VeryBuy\Payment\EsunBank\VirtualAccount\VerifyType;
    use VeryBuy\Payment\EsunBank\VirtualAccount\VirtualAccountBuilder;

    $companyId = 99123; // 特店代號

    $builder = new VirtualAccountBuilder($companyId, [
        'type' => VerifyType::NONE_BASE,    // builder 類別
        'length' => 14,                     // 虛擬帳號長度
        'number' => '393988912',            // (14碼)自訂碼 length:9 ; (13碼)自訂碼 length:8
    ]);

    $vaccount = $builder->make();
```

### Use ResponseVerifier verify response


```php
<?php
    use VeryBuy\Payment\EsunBank\VirtualAccount\Response\ResponseVerifier;

    $verifier = new ResponseVerifier({response encrypted string});

    $verifier->getTradedAt();       // 交易時間
    $verifier->getPaidAt();         // 付款時間
    $verifier->getVirtualAccount(); // 取得被付款虛擬帳號
    $verifier->getAmount();         // 付款金額
```


 > 當接收端收到銀行端的回應時 (METHOD:`POST`) 需回應 `OK` & HTTP STATUS CODE: `200`


--

 - [x] VerifyType::NONE_BASE              (不檢)
 - [x] VerifyType::SINGLE_BASE            (單碼檢核)
 - [x] VerifyType::SINGLE_AMOUNT          (單碼檢核含金額)
 - [x] VerifyType::SINGLE_AMOUNT_DATE     (單碼檢核含金額及日期)
 - [x] VerifyType::SINGLE_AMOUNT_DATETIME (單碼檢核含金額及日期時間)
 - [x] VerifyType::DOUBLE_BASE            (雙碼檢核)
 - [x] VerifyType::DOUBLE_AMOUNT          (雙碼檢核含金額)
 - [x] VerifyType::DOUBLE_AMOUNT_DATE     (雙碼檢核含金額及日期)
