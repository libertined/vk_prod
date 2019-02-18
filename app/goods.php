<?php

/**
 * Товарный листинг
 */

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
  /*$goodsInfo = \App\Cache\getGoodsInfoByIds($idList);

  if(!empty($goodsInfo)) {
    return $goodsInfo;
  }*/

  $query = sprintf(
    'SELECT * FROM goods WHERE id IN (%s)',
    implode(',', $idList)
  );

  $result = \App\DB\getQuery($query);

  $items = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $items[$row["id"]] = $row;
  }

  //\App\Cache\setGoodsInfoByIds($items);

  return $items;
}

function getGoodsNavigateSettings()
{
  $total = \App\Util\getAllGoodsAmount();
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
  $activePage = (int)($_GET[$config['page_code']] ?? 1);

  if(empty($activePage) || $activePage < 1) {
    $activePage = 1;
  } elseif($activePage > $total) {
    $activePage = $total;
  }

  return $activePage;
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

function getTitles()
{
  $config = \Config\getSettings()['list'];

  return $config['titles'];
}

function getPagination()
{
  $navParams = getGoodsNavigateSettings();
  $sortParams = getSortSettings();

  $config = \Config\getSettings()['list'];

  $left = max($navParams['page'] - $config['pagination']['left'], 1);
  $right = min($navParams['page'] + $config['pagination']['right'], $navParams['total']);

  $result = [];
  for($page = $left; $page <= $right; $page++) {
    $result[$page] = [
      'PAGE' => $page,
      'LINK' => sprintf('?page=%s&sort=%s', $page, $sortParams['sort_string']),
      'ACTIVE' => false
    ];
    if($page == $navParams['page']) {
      $result[$page]['ACTIVE'] = true;
    }
  }

  if($config['pagination']['showLast'] && !isset($result[$navParams['total']])) {
    $result[$navParams['total']] = [
      'PAGE' => $navParams['total'],
      'LINK' => sprintf('?page=%s&sort=%s', $navParams['total'], $sortParams['sort_string']),
      'ACTIVE' => false
    ];
  }

  if($config['pagination']['showFirst'] && !isset($result[1])) {
    $curPage = [
      1 => [
      'PAGE' => 1,
      'LINK' => sprintf('?page=%s&sort=%s', 1, $sortParams['sort_string']),
        'ACTIVE' => false
    ]];
    $result = array_merge($curPage, $result);
  }

  return $result;
}