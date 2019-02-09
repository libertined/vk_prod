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

function showSort(callable  $getSortParams)
{
  $sortParams = $getSortParams();
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <li class="menu-top__item <?=(($sortParams['sort_string'] == 'id_up')? 'menu-top__item--active': '')?>">
        <a href="?sort=id_up">ID по возрастанию</a>
      </li>
      <li class="menu-top__item <?=(($sortParams['sort_string'] == 'id_down')? 'menu-top__item--active': '')?>">
        <a href="?sort=id_down">ID по убыванию</a>
      </li>
      <li class="menu-top__item <?=(($sortParams['sort_string'] == 'price_up')? 'menu-top__item--active': '')?>">
        <a href="?sort=price_up">Цена по возрастанию</a>
      </li>
      <li class="menu-top__item <?=(($sortParams['sort_string'] == 'price_down')? 'menu-top__item--active': '')?>">
        <a href="?sort=price_down">Цена по убыванию</a>
      </li>
    </ul>
  </nav>
  <?
}

function showGoodsList($titles, $goodsList)
{
  if(empty($goodsList)) {
    echo '<p>Список товаров пуст</p>';
    return;
  }
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
      return sprintf('<img src="%s" width="50">', $value);
    case 'ACTIONS':
      return sprintf('<a href="edit.php?id=%s">Редактировать</a>', $productInfo["ID"]);
    default:
      return $value;
  }
}

function getTitleByGoodInfo($goodInfo)
{
  if(empty($goodInfo)) {
    return 'Создать товар';
  }

  return sprintf('Редактировать товар ID %s', $goodInfo['ID']);
}

function showEditForm($titles, $goodInfo)
{
  foreach($titles as $column => $name) {
    ?>
    <div class="good-edit__item">
      <p class="good-edit__item-title"><?=$name?></p>
      <input class="good-edit__text-field" type="text" placeholder="Введите <?=$name?>" value="<?=$goodInfo[$column]?>" name="<?=$column?>"/>
    </div>
  <?
  }
}

function showActionButtons()
{
  ?>
  <input class="good-edit__send" type="submit" value="Применить" name="good-edit-send">
  <input class="good-edit__send" type="submit" value="Удалить" name="good-edit-delete">
  <?
}

function showNavigate(callable  $getNavParams)
{
  $navParams = $getNavParams();
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <?for($page = 1; $page <= $navParams['total']; $page++):?>
        <?if($page == $navParams['page']):?>
          <li class="menu-top__item menu-top__item--active"><span><?=$page?></span></li>
        <?else:?>
          <li class="menu-top__item"><a href="?page=<?=$page?>"><?=$page?></a></li>
        <?endif;?>
      <?endfor;?>
    </ul>
  </nav>
<?
}