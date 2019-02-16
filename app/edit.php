<?php

/**
 * Форма редактирования
 */

namespace App\Edit;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($application.'/imageuploader.php');
require_once($application.'/pagesettings.php');
require_once($application.'/cache.php');
require_once($config.'/settings.php');

function getMenu()
{
  $config = \Config\getSettings()['edit'];

  $backPage = \App\Util\getCurrentPageSettings();
  $config['menu']['back']['LINK'] = !empty($backPage) ? $backPage : $config['menu']['back']['LINK'];

  return $config['menu'];
}

function getGoodInfo()
{
  $id = getGoodIdFromUrl();
  if(is_null($id)) {
    return [];
  }

  $query = sprintf('SELECT * FROM goods WHERE id = %s', $id);

  $result = \App\DB\getQuery($query);
  $row = mysqli_fetch_assoc($result);

  return \App\Util\getFormattedGoodInfo($row);
}

function getGoodIdFromUrl()
{
  $config = \Config\getSettings()['edit'];

  if(!isset($_GET[$config['good_code']])) {
    return null;
  }

  $goodId = (int)$_GET[$config['good_code']] ?: 0;

  return $goodId;
}

function processingGoodActions($url)
{
  $config = \Config\getSettings()['edit'];

  $goodId = getGoodIdFromUrl();
  $isSave = isset($_POST[$config['save_code']]);
  $isDelete = isset($_POST[$config['delete_code']]);

  if(!$isSave && !$isDelete) {
    return true;
  }

  $result = null;
  $link = null;

  if($isSave && !empty($goodId)) {
    $result = updateGood($goodId);
    if($result === true) {
      $link = sprintf('Location: %s', $url);
    }
  } elseif($isSave && empty($goodId)) {
    $result = createGood();
    if(is_int($result)) {
      $link = sprintf('Location: %s?%s=%s', $url, $config['good_code'], $result);
      $result = true;
      \App\PageSettings\clearSettings();
    }
  } elseif($isDelete && !empty($goodId)) {
    $result = deleteGood($goodId);
    if($result === true) {
      $link = sprintf('Location: index.php');
    }
  }

  if($result) {
    \App\Cache\clearAll();
  }

  if($result && !is_null($link)) {
    header($link);
  }

  return $result;
}

function createGood()
{
  $info = getInfoForSave();

  if(validate($info) !== true) {
    return validate($info);
  }

  if(empty($info['IMG'])) {
    unset($info['IMG']);
  }

  $preparedInfo = \App\Util\prepareGoodInfoForDB($info);

  $insertId = \App\DB\insertData($preparedInfo);

  return $insertId;
}

function deleteGood($id)
{
  $goodInfo = getGoodInfo($id);
  if(empty($goodInfo)) {
    return 'Товар с таким ID не найден';
  }

  if(\App\DB\deleteById($id)) {
    \App\ImageUploader\deleteImage($goodInfo['IMG']);
    return true;
  };

  return 'При удалении товара возникла ошибка';
}

// Вот тут могут быть конфликты, когда несколько человек редактируют одно и тоже
function updateGood($id)
{
  $goodInfo = getGoodInfo($id);
  if(empty($goodInfo)) {
    return 'Товар с таким ID не найден';
  }

  $info = getInfoForSave();

  if(isChanged($goodInfo)) {
    $fieldsDiff = getChangedFields($goodInfo, $info);
    return 'С момента открытия карточки товар был изменен. Если все еще хотите его отредактировать, внесите ваши изменения повторно.<br>'
            .implode('<br>', $fieldsDiff);
  }

  if(validate($info) !== true) {
    return validate($info);
  }

  if(empty($info['IMG'])) {
    unset($info['IMG']);
  }

  $preparedInfo = \App\Util\prepareGoodInfoForDB($info);

  $result = \App\DB\updateById($id, $preparedInfo);

  if($result && (!empty($info['IMG']) || $info['DELETE_IMG'])) {
    \App\ImageUploader\deleteImage($goodInfo['IMG']);
  }

  return $result;
}

function getChangedFields($oldFields, $newFields)
{
  $info['PRICE'] = \App\Util\clean_price($newFields['PRICE']);
  $oldFields['PRICE'] = \App\Util\clean_price($oldFields['PRICE']);
  $diff = [];
  foreach($newFields as $key => $value) {
    if(!isset($oldFields[$key])) {
      continue;
    }
    if($value != $oldFields[$key]) {
      $diff[$key] = sprintf('Сохраненное значение:%s - Введенное значение:%s', $oldFields[$key], $value);
    }
  }
  return $diff;
}

function isChanged($goodInfo)
{
  if(strtotime($goodInfo['MODIFIED']) > htmlspecialchars($_POST['SHOW_TIME'])) {
    return true;
  }
  return false;
}

function validate($info)
{
  if(empty($info['TITLE'])) {
    return 'Не задано название';
  }
  if(empty($info['PRICE'])) {
    return 'Не задана цена';
  }

  $sessionCode = md5(session_id());
  if($sessionCode != $info['SES_CODE']) {
    return 'Попытка передачи данных с другого хоста.';
  }

  if(!empty($info['NAME'])) {
    return 'А не бот ли вы, батенька?';
  }

  return true;
}

function getInfoForSave()
{
  $deleteImage = (isset($_POST["DELETE_IMG"]) && $_POST["DELETE_IMG"] == 'Y');
  return [
    'TITLE' => htmlspecialchars($_POST["TITLE"]),
    'DESC' => htmlspecialchars($_POST["DESC"]),
    'PRICE' => \App\Util\clean_price(htmlspecialchars($_POST["PRICE"])),
    'IMG' => \App\ImageUploader\uploadImage("IMG", $deleteImage),
    'DELETE_IMG' => $deleteImage,
    'SES_CODE' => htmlspecialchars($_POST["SES_CODE"]),
    'NAME' => htmlspecialchars($_POST["NAME"]),
  ];
}

function getTitles()
{
  $config = \Config\getSettings()['edit'];

  return $config['titles'];
}