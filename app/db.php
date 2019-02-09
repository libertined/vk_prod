<?php
/**
 *  Работа с базой данных
 */
namespace App\DB;

require_once('dependences.php');
require_once($config.'/settings.php');

/**
 * Создание соединения в БД
 * @return \mysqli
 */
function createDBConnection()
{
  $config = \Config\getSettings()['db'];

  $connection = mysqli_connect($config['HOST'], $config['USER'], $config['PASSWORD'], $config['DBNAME']);

  if (!$connection) {
    echo "Ошибка подключения к базе данных. Код ошибки: ".mysqli_connect_error();
    exit;
  }

  return $connection;
}

/**
 * Закрытие соединения с БД
 * @param mysqli  $connection
 */
function closeDBConnection($connection) {
  mysqli_close($connection);
}

/**
 * Запрос к БД
 * @param $query
 * @return bool|\mysqli_result
 */
function getQuery($query){
  $connection = createDBConnection();
  $result = mysqli_query($connection, $query);
  closeDBConnection($connection);

  return $result;
}

function insertData($info) {
  $connection = createDBConnection();

  $values = [];
  foreach($info as $value) {
    $values[] = mysqli_real_escape_string($connection, $value);
  }

  $query = sprintf(
    "INSERT INTO goods (%s) VALUES ('%s')",
    implode(',', array_keys($info)),
    implode("','", $values)
  );

  $insert_row = mysqli_query($connection, $query);
  closeDBConnection($connection);

  return $insert_row;
}

