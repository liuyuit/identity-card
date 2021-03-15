<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 3/12/2021
 * Time: 6:21 PM
 */
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
