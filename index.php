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
        <?\Templates\showNavigate("\App\Goods\getPagination")?>
        <?\Templates\showGoodsList("\App\Goods\getTitles", \App\Goods\getGoodsList())?>
        <div class="minimize-block">
          <span class="minimize-block__link">Развернуть</span>
          <?\Templates\showFullNavigate("\App\Goods\getGoodsNavigateSettings", "\App\Goods\getSortSettings")?>
        </div>
    </div>
  </div>
<script src="assets/js/script.js"></script>
</body>
</html>