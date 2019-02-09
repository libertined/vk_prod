<?php

namespace App\Util;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($config.'/settings.php');

function getFormattedGoodInfo($goodDBInfo)
{
  $config = \Config\getSettings()['general'];

  return [
    "ID" => $goodDBInfo["id"],
    "IMG" => $config['images_path'].$goodDBInfo["image"],
    "TITLE" => $goodDBInfo["name"],
    "DESC" => $goodDBInfo["description"],
    "PRICE" => calculatePrice($goodDBInfo["price"]),
  ];
}

function prepareGoodInfoForDB($goodInfo)
{
  $config = \Config\getSettings()['general'];

  return [
    "image" => str_replace($config['image_full_path'], "", $goodInfo["IMG"]),
    "name" => $goodInfo["TITLE"],
    "description" => $goodInfo["DESC"],
    "price" => calculatePriceForDB($goodInfo["PRICE"]),
  ];
}

function calculatePrice($price)
{
  return (int)$price / 100;
}

function calculatePriceForDB($price)
{
  return (int)($price * 100);
}