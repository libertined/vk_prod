<?php
/**
 * Работа с кэшированием
 */

namespace App\Cache;

require_once('dependences.php');
require_once($cacheinstance.'/memcached.php');
require_once($config.'/settings.php');

/**
 * Получение зачения по ключу
 * @param $key
 */
function getValueByKey($key)
{
  $res =\App\CacheInstance\Memcached\getValueByKey($key);
  return $res;
}

/**
 * Добавление значения по ключу
 * @param $key
 * @param $value
 */
function setValueByKey($key, $value)
{
  $res =\App\CacheInstance\Memcached\setValueByKey($key, $value);
  return $res;
}

/**
 * На мой взгляд Memcached нужно использовать для хранения небольших массивов данных, поэтому большие разумно хранить где-нибудь еще
 * @param $key
 * @param $value
 */
function setBigValueByKey($key, $value)
{
  $res =\App\CacheInstance\Memcached\setValueByKey($key, $value);
  return $res;
}

/**
 * Парный к setBigValueByKey
 * @param $key
 */
function getBigValueByKey($key)
{
  $res =\App\CacheInstance\Memcached\getValueByKey($key);
  return $res;
}

function deleteByKey($key)
{
  $res =\App\CacheInstance\Memcached\deleteByKey($key);
  return $res;
}

/**
 * Очищает все элементы
 * @return bool
 */
function clearAll()
{
  $res =\App\CacheInstance\Memcached\clearAll();
  return $res;
}

function setAllGoodsAmount($amount)
{
  $config = \Config\getSettings()['cache'];

  $key = $config['total'];
  $result = setValueByKey($key, $amount);

  return $result;
}

function getAllGoodsAmount()
{
  $config = \Config\getSettings()['cache'];

  $key = $config['total'];
  $result = getValueByKey($key);

  if(empty($result)) {
    return null;
  }

  return $result;
}

function getPageIds(int $pageNumber, string $sort)
{
  $config = \Config\getSettings()['cache'];

  $key = sprintf($config['page'], $pageNumber, $sort);

  $result = getValueByKey($key);

  if(empty($result)) {
    return [];
  }

  return explode($config['delimiter'], $result);
}

function setPageIds(int $pageNumber, string $sort, $idsList) {
  if(empty($idsList)) {
    return true;
  }
  $config = \Config\getSettings()['cache'];

  $key = sprintf($config['page'], $pageNumber, $sort);
  $ids = implode($config['delimiter'], $idsList);

  return setValueByKey($key, $ids);
}

function getGoodsInfoByIds($ids)
{
  if(empty($ids)) {
    return true;
  }

  $config = \Config\getSettings()['cache'];

  sort($ids);
  $key = sprintf($config['goods_info'], md5(implode($config['delimiter'], $ids)));
  $result = getBigValueByKey($key);

  if(empty($result)) {
    return [];
  }

  return $result;
}

function setGoodsInfoByIds($info) {
  $keys = array_keys($info);

  if(empty($keys)) {
    return true;
  }

  $config = \Config\getSettings()['cache'];

  sort($keys);
  $key = sprintf($config['goods_info'], md5(implode($config['delimiter'], $keys)));

  $result = setBigValueByKey($key, $info);

  return $result;
}