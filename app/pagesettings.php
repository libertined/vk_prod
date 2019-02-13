<?php

namespace App\PageSettings;

require_once('dependences.php');

function setSettings($key, $value)
{
  if(empty($key)) {
    return;
  }

  $_SESSION['settings'][$key] = $value;
}

function getSettings($key)
{
  return $_SESSION['settings'][$key] ?? '';
}

function clearSettings()
{
  $_SESSION['settings'] = [];
}