<?php

namespace App\Goods;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($config.'/settings.php');

function getMenu()
{
  $config = \Config\getSettings()['list'];

  return $config['menu'];
}

function getGoodsList()
{
  $navParams = getGoodsNavigateSettings();

  if(empty($navParams)) {
    return [];
  }

  $sortParams = getSortSettings();

  $query = sprintf(
    'SELECT * FROM goods ORDER BY %s %s LIMIT %s OFFSET %s',
    $sortParams['sort'], $sortParams['order'],
    $navParams['size'], $navParams['offset']
  );

  $result = \App\DB\getQuery($query);

  $items = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $items[$row["id"]] = \App\Util\getFormattedGoodInfo($row);
  }

  return $items;
}

function getGoodsNavigateSettings()
{
  $total = getAllGoodsAmount();
  if(empty($total)) {
    return [];
  }

  $config = \Config\getSettings()['list'];

  $pages = ceil($total/$config['gods_on_page']);
  $pageNumber = getPageNumber($pages);

  return [
    'total' => $pages,
    'page' => getPageNumber($pages),
    'size' => $config['gods_on_page'],
    'offset' => $config['gods_on_page']*($pageNumber-1)
  ];
}

function getPageNumber($total)
{
  $config = \Config\getSettings()['list'];
  $activePage = (int)(isset($_GET[$config['page_code']]) ? $_GET[$config['page_code']] : 1);

  if(empty($activePage) || $activePage < 1) {
    $activePage = 1;
  } elseif($activePage > $total) {
    $activePage = $total;
  }

  return $activePage;
}

function getAllGoodsAmount()
{
  $query = 'SELECT COUNT(id) FROM goods';

  $result = \App\DB\getQuery($query);

  $row = mysqli_fetch_assoc($result);

  return reset($row);
}

function getSortSettings()
{
  $config = \Config\getSettings()['list'];

  $sortType = (isset($_GET[$config['sort_code']]) ? htmlspecialchars($_GET[$config['sort_code']]) : $config['default_sort']);

  if(!in_array($sortType, $config['sort'])) {
    $sortType = $config['default_sort'];
  }

  switch ($sortType) {
    case $config['sort']['price_asc']:
      $sort = 'price';
      $order = 'ASC';
      break;
    case $config['sort']['price_desc']:
      $sort = 'price';
      $order = 'DESC';
      break;
    case $config['sort']['id_desc']:
      $sort = 'id';
      $order = 'DESC';
      break;
    default:
      $sort = 'id';
      $order = 'ASC';
  }

  return [
    'sort_string' => $sortType,
    'sort' => $sort,
    'order' => $order
  ];
}