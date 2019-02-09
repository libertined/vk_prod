<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');

$goodsList = [
  1 => [
    "ID" => 1,
    "IMG" => "assets/images/img1.jpg",
    "TITLE" => "Плот",
    "DESC" => "Небольшой вместительный плот",
    "PRICE" => "100",
  ],
  2 => [
    "ID" => 2,
    "IMG" => "assets/images/img2.jpg",
    "TITLE" => "Ганзейский корабль",
    "DESC" => "Огромный парусник, оснащенный по последнему слову техники 15 века",
    "PRICE" => "10000",
  ],
  3 => [
    "ID" => 3,
    "IMG" => "assets/images/img3.jpg",
    "TITLE" => "Фрегат",
    "DESC" => "Военный парусный корабль, типа фрегат",
    "PRICE" => "500.5",
  ],
  4 => [
    "ID" => 4,
    "IMG" => "assets/images/img4.jpg",
    "TITLE" => "Автомобиль Левассор",
    "DESC" => "Раритетное авто 1903 года, в хорошем состоянии",
    "PRICE" => "99000000.99",
  ],
  5 => [
    "ID" => 5,
    "IMG" => "assets/images/img5.jpg",
    "TITLE" => "Даймлер",
    "DESC" => "Покоритель сердец и звезда мирового автопрома",
    "PRICE" => "99999999.99",
  ],
];
?>
<!DOCTYPE html>
<html>
<head>
  <?\Templates\showHeader('Список товаров')?>
</head>
<body>
  <div class="layoutCenterWrapper">
    <?\Templates\showMenue()?>
    <div class="main-part clearfix">
        <h1 class="main-title">Список товаров</h1>
        <?\Templates\showSort()?>
        <?\Templates\showGoodsList($goodsList)?>
    </div>
  </div>
</body>
</html>