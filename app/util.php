<?php

/**
 * Функции общего назначения
 */

namespace App\Util;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/cache.php');
require_once($application.'/pagesettings.php');
require_once($config.'/settings.php');

/**
 * Форматированный вид информации по товару
 * @param $goodDBInfo
 * @return array
 */
function getFormattedGoodInfo($goodDBInfo)
{
  $config = \Config\getSettings()['general'];

  return [
    "ID" => $goodDBInfo["id"],
    "IMG" => $goodDBInfo["image"] ? $config['images_path'].$goodDBInfo["image"] : '',
    "TITLE" => $goodDBInfo["name"],
    "DESC" => $goodDBInfo["description"],
    "PRICE" => getFormattedPrice(calculatePrice($goodDBInfo["price"])),
    "MODIFIED" => $goodDBInfo["modified"],
  ];
}

function getFormattedPrice($price)
{
  return number_format($price, 2, '.', ' ');
}

/**
 * Подготовленная для вставки в базу информация по товару
 * @param $goodInfo
 * @return array
 */
function prepareGoodInfoForDB($goodInfo)
{
  $config = \Config\getSettings()['general'];

  $result = [
    "name" => $goodInfo["TITLE"],
    "description" => $goodInfo["DESC"],
    "price" => calculatePriceForDB($goodInfo["PRICE"]),
  ];

  if(!empty($goodInfo["IMG"])) {
    $result["image"] = str_replace($config['image_full_path'], "", $goodInfo["IMG"]);
  }

  if($goodInfo["DELETE_IMG"]) {
    $result["image"] = '';
  }

  return $result;
}

/**
 * Рассчет цены, после получения данных из базы
 * @param $price
 * @return string
 */
function calculatePrice($price)
{
  return substr($price, 0, -2).".".substr($price, -2);
}

/**
 * Рассчет цены для сохранения данных в базу
 * @param $price
 * @return int
 */
function calculatePriceForDB($price)
{
  $price = explode('.',  $price);
  return (int)$price[0] * 100 + (int)$price[1];
}

/**
 * Приведение цены к корректному виду
 * @param $value
 * @param string $default
 * @return int|mixed|string
 */
function clean_price($value, $default = '')
{
  $value = mb_ereg_replace('[^0-9.,]', '', $value);
  $value = mb_ereg_replace('[,]+', ',', $value);
  $value = mb_ereg_replace('[.]+', '.', $value);

  $pos_1 = mb_strpos($value, '.');
  $pos_2 = mb_strpos($value, ',');

  if ($pos_1 && $pos_2) {
    // 1,000,000.00
    $value = mb_substr($value . '00', 0, $pos_1 + 3);
    $value = str_replace(',', '', $value);
  } elseif ($pos_1) {
    // 1000000.00
    $value = mb_substr($value . '00', 0, $pos_1 + 3);
  } elseif ($pos_2) {
    if ((mb_strlen($value) - $pos_2) == 3) {
      // 10,00
      $value = str_replace(',', '.', $value);
    } else {
      // 100,000,000
      $value = str_replace(',', '', $value) . '.00';
    }
  } elseif (mb_strlen($value) == 0) {
    return $default;
  } else {
    $value = $value . '.00';
  }

  return ($value == '0.00') ? 0 : $value;
}

function getAllGoodsAmount()
{
  $cachedValue = \App\Cache\getAllGoodsAmount();

  if(!empty($cachedValue)) {
    return $cachedValue;
  }

  $query = 'SELECT COUNT(id) FROM goods';

  $result = \App\DB\getQuery($query);
  $row = mysqli_fetch_assoc($result);
  $amount = reset($row);

  \App\Cache\setAllGoodsAmount($amount);

  return $amount;
}

function prolog()
{
  session_start();
}

function setCurrentPageSettings()
{
  $config = \Config\getSettings()['general'];
  \App\PageSettings\setSettings($config['currentListLink'], $_SERVER['REQUEST_URI']);
}

function getCurrentPageSettings()
{
  $config = \Config\getSettings()['general'];
  return \App\PageSettings\getSettings($config['currentListLink']);
}