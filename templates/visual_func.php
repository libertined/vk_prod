<?php

namespace Templates;
/**
 * Функции связанные с визуализацией информации
 */
/**
 * @param string $title
 */
function showHeader($title = '')
{
  if(empty($title)) {
    $title = 'Тестовое задание Аникеевой Дианы';
  }
  ?>
    <title><?=$title?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="assets/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <?
}

function showMenue()
{
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <li class="menu-top__item"><a href="edit.php">Добавить товар</a></li>
    </ul>
  </nav>
  <?
}

function showSort($active = '')
{
  if(empty($active)) {
    $active = 'id';
  }
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <li class="menu-top__item <?=(($active == 'id')? 'menu-top__item--active': '')?>"><a href="?sort=id">По ID</a></li>
      <li class="menu-top__item <?=(($active == 'price')? 'menu-top__item--active': '')?>"><a href="?sort=price">По Цене</a></li>
    </ul>
  </nav>
  <?
}

function showGoodsList($goodsList)
{
  if(empty($goodsList)) {
    echo '<p>Список товаров пуст</p>';
    return;
  }
  $titles = [
    "ID" => "ID",
    "IMG" => "Изображение",
    "TITLE" => "Название",
    "DESC" => "Описание",
    "PRICE" => "Цена",
    "ACTIONS" => ""
  ];
  ?>
  <table class="goods-table" width="100%">
    <?showTitles($titles);?>
    <?showTableRows($goodsList, array_keys($titles))?>
  </table>
  <?
}

function showTitles($titles = null)
{
  if(empty($titles)) {
    return;
  }
  ?>
    <tr class="goods-table__head">
      <?foreach ($titles as $key => $name):?>
        <th><?=$name?></th>
      <?endforeach;?>
    </tr>
  <?
}

function showTableRows($goodsList, $columns)
{
  if(empty($goodsList) || empty($columns)) {
    return;
  }
  ?>
    <?foreach ($goodsList as $product):?>
    <tr class="goods-table__row" data-user="<?=$product["ID"]?>">
      <?foreach ($columns as $column):?>
        <td class="goods-table__<?=strtolower($column)?>"><?=getValueByType($column, $product);?></td>
      <?endforeach;?>
    </tr>
    <?endforeach;?>
  <?
}

function getValueByType($column, $productInfo)
{
  $value = isset($productInfo[$column]) ? $productInfo[$column] : '';
  switch($column) {
    case 'IMG':
      return '<img src="'.$value.'" width="50">';
    case 'ACTIONS':
      return '<a href="edit.php?id='.$productInfo["ID"].'">Редактировать</a>';
    default:
      return $value;
  }
}