<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');
require_once($application.'/edit.php');

$result = \App\Edit\processingGoodActions($_SERVER['REQUEST_URI']);
$currentGood = \App\Edit\getGoodInfo();
?>
<!DOCTYPE html>
<html>
<head>
  <?\Templates\showHeader(\Templates\getTitleByGoodInfo($currentGood))?>
</head>
<body>
<?if($result !== true):?>
  <div class="error"><?=$result?></div>
<?endif;?>
<div class="layoutCenterWrapper">
  <?\Templates\showMenu("\App\Edit\getMenu")?>
  <div class="main-part clearfix">
    <h1 class="main-title"><?=\Templates\getTitleByGoodInfo($currentGood)?></h1>
    <div class="good-edit">
      <form class="good-edit__form" name="good-edit-form" enctype="multipart/form-data" action="" method="post" id="good-edit_form">
        <?\Templates\showEditForm("\App\Edit\getTitles", $currentGood)?>
        <?\Templates\showActionButtons($currentGood)?>
      </form>
    </div>
  </div>
</div>
</body>
</html>