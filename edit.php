<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');
require_once($application.'/edit.php');

$result = \App\Edit\processingGoodActions($_SERVER['REQUEST_URI']);
$currentGood = \App\Edit\getGoodInfo();
$goodId = array_key_exists("ID", $currentGood) ? $currentGood["ID"] : "";
?>
<!DOCTYPE html>
<html>
<head>
  <?\Templates\showHeader(\Templates\getTitleByGoodInfo($currentGood))?>
</head>
<body>
<div class="layoutCenterWrapper">
  <?\Templates\showMenu("\App\Edit\getMenu")?>
  <div class="main-part clearfix">
    <h1 class="main-title"><?=\Templates\getTitleByGoodInfo($currentGood)?></h1>
    <?if($result !== true):?>
      <div class="error"><?=$result?></div>
    <?endif;?>
    <?if(!is_null($goodId)):?>
    <div class="good-edit col-xs-7">
      <form class="good-edit__form" name="good-edit-form" enctype="multipart/form-data" action="" method="post" id="good-edit_form">
        <?\Templates\showEditForm("\App\Edit\getTitles", $currentGood)?>
        <?\Templates\showActionButtons($currentGood)?>
      </form>
    </div>
    <?else:?>
      <div class="error">Такого товара нет, редактирование невозможно. Но вы можете <a href="edit.php">создать новый товар</a>.</div>
    <?endif;?>
  </div>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>