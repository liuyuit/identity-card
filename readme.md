# Identity card

## references

> https://github.com/yl/id-card/blob/master/src/IDCard.php#L9
>
> https://www.cnblogs.com/itbsl/p/11282677.html
>
> https://www.jianshu.com/p/94f69dd6cd46

a composer package to parse  identity card of China

## install

```
compser require liuyuit/identity-card
```

## use 

```
<?php
ini_set('display_errors', 1);
error_reporting(-1);
require_once __DIR__ . '/vendor/autoload.php';

use liuyuit\IdentityCard\IdentityCard;

$identityCardNo = '332522198908021574';
$identityCard = new IdentityCard($identityCardNo);

$identityCard->check(); // true
try {
    $birthday = $identityCard->birthday();
    echo $birthday . PHP_EOL; // 19890802
    $identityCard->age();
    $gender = $identityCard->gender();
    echo $gender; // 1
    $identityCard->constellation();
    $identityCard->zodiac();
} catch (\liuyuit\IdentityCard\InvalidIdentityCardException $e) {
}
```