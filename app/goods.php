<?php

namespace App\Goods;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($application.'/cache.php');
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

  $idsList = getGoodsListKeys($navParams, $sortParams);

  if(empty($idsList)) {
    return [];
  }

  $goodsInfo = getInfoByIds($idsList);

  $resultList = [];
  foreach($idsList as $id) {
    $resultList[$id] = \App\Util\getFormattedGoodInfo($goodsInfo[$id]);
  }

  return $resultList;
}

function getGoodsListKeys($navParams, $sortParams)
{
  $idsList = \App\Cache\getPageIds($navParams['page'], $sortParams['sort_string']);

  if(!empty($idsList)) {
    return $idsList;
  }

  $query = sprintf(
    'SELECT id FROM goods ORDER BY %s %s LIMIT %s OFFSET %s',
    $sortParams['sort'], $sortParams['order'],
    $navParams['size'], $navParams['offset']
  );

  $result = \App\DB\getQuery($query);

  $items = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row["id"];
  }

  \App\Cache\setPageIds($navParams['page'], $sortParams['sort_string'], $items);

  return $items;
}

function getInfoByIds($idList)
{
  if(empty($idList)) {
    return [];
  }
  $goodsInfo = \App\Cache\getGoodsInfoByIds($idList);

  if(!empty($goodsInfo)) {
    return $goodsInfo;
  }

  $query = sprintf(
    'SELECT * FROM goods WHERE id IN (%s)',
    implode(',', $idList)
  );

  $result = \App\DB\getQuery($query);

  $items = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $items[$row["id"]] = $row;
  }

  \App\Cache\setGoodsInfoByIds($items);

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

function getSortSettings()
{
  $config = \Config\getSettings()['list'];

  if(!isset($_GET[$config['sort_code']]) || !in_array($_GET[$config['sort_code']], $config['sort'])) {
    $sortType = $config['default_sort'];
  } else {
    $sortType = $_GET[$config['sort_code']];
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