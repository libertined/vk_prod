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
        <div class="minimize-block">
          <span class="minimize-block__link">Развернуть</span>
          <?\Templates\showNavigate("\App\Goods\getGoodsNavigateSettings", "\App\Goods\getSortSettings")?>
        </div>
        <?\Templates\showGoodsList("\App\Goods\getTitles", \App\Goods\getGoodsList())?>
        <?\Templates\showNavigate("\App\Goods\getGoodsNavigateSettings", "\App\Goods\getSortSettings")?>
    </div>
  </div>
<script src="assets/js/script.js"></script>
</body>
</html>