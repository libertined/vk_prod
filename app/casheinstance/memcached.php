<?php
/**
 * Работа с кэшированием в Memcached
 */

namespace App\CacheInstance\Memcached;

require_once(dirname(__DIR__, 1).'/dependences.php');
require_once($config.'/settings.php');

/**
 * Создание соединения c Memcached
 */
function createConnection()
{
  $config = \Config\getSettings()['memcached'];

  $connection = memcache_connect($config['host'], $config['port']);

  if ($connection === false) {
    echo "Ошибка подключения к Memcached";
    exit();
  }

  return $connection;
}

/**
 * Закрытие соединения с Memcached
 */
function closeConnection($connection) {
  memcache_close($connection);
}

/**
 * Получение зачения по ключу
 * @param $key
 */
function getValueByKey($key)
{
  $connection = createConnection();
  $res = memcache_get($connection, $key);
  closeConnection($connection);

  return $res;
}

/**
 * Добавление значения по ключу
 */

function setValueByKey($key, $value)
{
  $config = \Config\getSettings()['memcached'];

  $connection = createConnection();
  $res = memcache_set($connection, $key, $value, $config['is_compressed'], $config['expire_time']);
  closeConnection($connection);

  return $res;
}

/**
 * Очищает все элементы в Memcached
 * @return bool
 */
function clearAll() {
  $connection = createConnection();
  $res = memcache_flush($connection);
  closeConnection($connection);

  return $res;
}