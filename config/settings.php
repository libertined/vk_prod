<?php

namespace Config;

function getSettings()
{
  return [
    'db' => include(__DIR__.'/db_config.php'),
    'general' => include(__DIR__.'/general.php'),
    'list' => include(__DIR__.'/list.php'),
    'edit' => include(__DIR__.'/edit.php'),
    'cache' => include(__DIR__.'/cache.php'),
    'memcached' => include(__DIR__.'/memcached.php'),
  ];
}