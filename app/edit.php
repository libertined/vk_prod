<?php

namespace App\Edit;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($application.'/imageuploader.php');
require_once($config.'/settings.php');

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

  $result = '';

  if($isSave && !empty($goodId)) {

  } elseif($isSave && empty($goodId)) {
    $result = createGood();
    if(is_int($result)) {
      header(sprintf('Location: %s?%s=%s', $url, $config['good_code'], $result));
    }
  } elseif($isDelete && !empty($goodId)) {

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