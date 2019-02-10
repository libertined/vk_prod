<?php

namespace App\Edit;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($application.'/imageuploader.php');
require_once($config.'/settings.php');
require_once($application.'/cache.php');

function getMenu()
{
  $config = \Config\getSettings()['edit'];

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

  $result = false;
  $link = null;

  if($isSave && !empty($goodId)) {
    $result = updateGood($goodId);
  } elseif($isSave && empty($goodId)) {
    $resultId = createGood();
    if(is_int($resultId)) {
      $result = true;
      $link = sprintf('Location: %s?%s=%s', $url, $config['good_code'], $result);
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

  if(!is_null($link)) {
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

  if(validate($info) !== true) {
    return validate($info);
  }

  if(empty($info['IMG'])) {
    unset($info['IMG']);
  }

  $preparedInfo = \App\Util\prepareGoodInfoForDB($info);

  $result = \App\DB\updateById($id, $preparedInfo);

  if($result && !empty($info['IMG'])) {
    \App\ImageUploader\deleteImage($goodInfo['IMG']);
  }

  return $result;
}

function validate($info)
{
  if(empty($info['TITLE'])) {
    return 'Не задано название';
  }
  if(empty($info['PRICE'])) {
    return 'Не задана цена';
  }

  return true;
}

function getInfoForSave()
{
  return [
    'TITLE' => htmlspecialchars($_POST["TITLE"]),
    'DESC' => htmlspecialchars($_POST["DESC"]),
    'PRICE' => htmlspecialchars($_POST["PRICE"]),
    'IMG' => \App\ImageUploader\uploadImage("IMG")
  ];
}