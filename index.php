<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');
require_once($application.'/goods.php');
?>
<!DOCTYPE html>
<html>
<head>
  <?\Templates\showHeader('Список товаров')?>
</head>
<body>
  <div class="layoutCenterWrapper">
    <?\Templates\showMenu("\App\Goods\getMenu")?>
    <div class="main-part clearfix">
        <h1 class="main-title">Список товаров</h1>
        <?\Templates\showSort("\App\Goods\getSortSettings")?>
        <?\Templates\showNavigate("\App\Goods\getGoodsNavigateSettings")?>
        <?\Templates\showGoodsList("\App\Goods\getTitles", \App\Goods\getGoodsList())?>
        <?\Templates\showNavigate("\App\Goods\getGoodsNavigateSettings")?>
    </div>
  </div>
</body>
</html>