## 介绍

此库是用于扩展验证规则,`id_card_number`。

### 环境需求

1. php >= 7.3
2. Composer

### 安装

```shell script
composer require chendujin/id-card-number
```
### 配置

#### Laravel环境无需配置

#### Lumen

将下面代码放入 `bootstrap/app.php`

```shell script
$app->register(Chendujin\IdCardNumber\ServiceProvider::class);
```

### 使用

```php
<?php

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_number' => 'required|id_card_number'
    ]);
   
    if ($validator->fails()) {
        return new JsonResponse([
            'state' => 'error',
            'message' => $validator->errors()->first(),
        ]);
    }   

}
```

更多方法:

```php
$IDCard = new \Chendujin\IdCardNumber\IdCardNumber('身份证号码');

// 根据身份证号，自动返回对应的身份证地址
$IDCard->getAddress();

// 根据身份证号，自动返回对应的性别
$IDCard->getSex();

// 根据身份证号，自动返回对应的生日
$IDCard->getBirthday();

// 根据身份证号，自动返回对应的星座
$IDCard->getIDCardXZ();

// 根据身份证号，自动返回对应的生肖
$IDCard->getIDCardSX();

// 根据身份证号，自动返回对应的省、自治区、直辖市代
$IDCard->getProvince();
```

### 单元测试

```shell script
./vendor/bin/phpunit
```

更多示例可以查看 `IdCardNumberTest.php`, 该单元测试的测试用例来源于互联网，如果侵犯了您的隐私，请联系 `thomas.chen.jobs@gmail.com`，我会在第一时间删除。