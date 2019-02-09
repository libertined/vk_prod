<?php
require_once('app/dependences.php');
require_once($templates.'/visual_func.php');
require_once($application.'/fixtures.php');

$currentGood = $goodsList[$_GET['id']];
?>
<!DOCTYPE html>
<html>
<head>
  <?\Templates\showHeader(\Templates\getTitleByGoodInfo($currentGood))?>
</head>
<body>
<div class="layoutCenterWrapper">
  <div class="main-part clearfix">
    <h1 class="main-title"><?=\Templates\getTitleByGoodInfo($currentGood)?></h1>
    <div class="good-edit">
      <form class="good-edit__form" name="good-edit-form" action="" method="post" id="good-edit_form">
        <?\Templates\showEditForm($editTitles, $currentGood)?>
        <?\Templates\showActionButtons()?>
      </form>
    </div>
  </div>
</div>
</body>
</html>