<?php
/**
 * Скрипт для заполнения таблицы с товарами тестовыми данными
 */
require_once('../app/dependences.php');
require_once($application.'/fixtures.php');
require_once($application.'/db.php');
require_once($application.'/util.php');

$connection = \App\DB\createDBConnection();

$images = array_unique(array_column($goodsList, "IMG"));
$imagesCount = count($images);
$desc = array_column($goodsList, "DESC");
$descCount = count($desc);
$step = 1000;

for($i=1; $i <= $step; $i++) {
  $price = '';
  if(rand(0, 100)%2) {
    $price = ".".rand(0, 99);
  }
  $newGood = [
    "TITLE" => sprintf("Название %s-%s", $i, rand(0, 100)),
    "DESC" => $desc[rand(0, $descCount-1)],
    "IMG" => $images[rand(0, $imagesCount-1)],
    "PRICE" => rand(0, 10000000000).$price
  ];
  $preparedInfo = \App\Util\prepareGoodInfoForDB($newGood);
  $insertId = \App\DB\insertData($preparedInfo);
  print_r($insertId."<br>");
}
