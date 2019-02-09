<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');
require_once($application.'/fixtures.php');
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
        <?\Templates\showGoodsList($titles, $goodsList)?>
    </div>
  </div>
</body>
</html>