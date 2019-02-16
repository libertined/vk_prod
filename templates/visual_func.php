<?php
/**
 * Функции связанные с визуализацией информации
 */

namespace Templates;

/**
 * Вывод хедера
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

/**
 * Отображение меню
 * @param callable $getMenuList
 * @return string
 */
function showMenu(callable $getMenuList)
{
  $list = $getMenuList();
  if(empty($list)) {
    return '';
  }
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <?foreach ($list as $link):?>
        <li class="menu-top__item">
          <a href="<?=$link['LINK']?>" <?if(isset($link['CLASS'])):?>class="<?=$link['CLASS']?>"<?endif;?>><?=$link['TITLE']?></a>
        </li>
      <?endforeach;?>
    </ul>
  </nav>
  <?
}

/**
 * Отображение сортировки
 * @param callable $getSortParams
 */
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

/**
 * Отображение товарного листинга в виде таблицы
 * @param callable $getTitles
 * @param $goodsList
 */
function showGoodsList(callable $getTitles, $goodsList)
{
  $titles = $getTitles();

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

/**
 * Вывод заголовков таблицы
 * @param null $titles
 */
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

/**
 * Вывод табличных данных
 * @param $goodsList
 * @param $columns
 */
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

/**
 * Вывод данных в зависимости от типа
 * @param $column
 * @param $productInfo
 * @return string
 */
function getValueByType($column, $productInfo)
{
  $value = $productInfo[$column] ?? '';
  switch($column) {
    case 'IMG':
      if(!empty($value)) {
        $value = sprintf('<img src="%s" width="100">', $value);
      }
      return $value;
    case 'ACTIONS':
      return sprintf('<a href="edit.php?id=%s">Редактировать</a>', $productInfo["ID"]);
    default:
      return $value;
  }
}

/**
 * Отображение заголовка страницы редактирования/создания товара
 * @param $goodInfo
 * @return string
 */
function getTitleByGoodInfo($goodInfo)
{
  if(empty($goodInfo)) {
    return 'Создать товар';
  }

  return sprintf('Редактировать товар ID %s', $goodInfo['ID']);
}

/**
 * Поля для формы редактирования товара
 * @param callable $getTitles
 * @param $goodInfo
 */
function showEditForm(callable $getTitles, $goodInfo)
{
  $titles = $getTitles();
    ?>
  <?foreach($titles as $column => $name):?>
    <div class="good-edit__item clearfix">
      <p class="good-edit__item-title"><?=$name?></p>
      <?=getFieldByType($column, $name, $goodInfo)?>
    </div>
  <?endforeach;?>
  <?
  showSafetyElements();
}

function showSafetyElements()
{
  $sessionCode = md5(session_id());
  ?>
  <input type="hidden" name="SHOW_TIME" value="<?=time()?>">
  <input type="hidden" name="SES_CODE" value="<?=$sessionCode?>">
  <input type="text" name="NAME" value="" class="good-edit__who_is">
  <?
}

/**
 * Вывод поля редактирования в зависимости от типа
 * @param $column
 * @param $name
 * @param $productInfo
 * @return string
 */
function getFieldByType($column, $name,  $productInfo)
{
  $value = $productInfo[$column] ?? '';
  switch($column) {
    case 'IMG':
      $resultStr = '';
      if(!empty($value)) {
        $resultStr = sprintf('<img src="%s" width="150" class="good-edit__image">', $value);
        $resultStr .= '<br><label><input type="checkbox" name="DELETE_IMG" value="Y"> Удалить</label><br><br>';
      }
      $resultStr .= sprintf('<input name="%s" type="file" />', $column);
      return $resultStr;
    case 'DESC':
      return sprintf(
        '<textarea class="good-edit__text-desc" type="text" placeholder="Введите %s"  name="%s"/>%s</textarea>',
        $name, $column, $value
      );
    default:
      return sprintf(
        '<input class="good-edit__text-field" type="text" placeholder="Введите %s" value="%s" name="%s"/>',
        $name, $value, $column
        );
  }
}

/**
 * Вывод возможных действий с формой
 * @param $currentGood
 */
function showActionButtons($currentGood)
{
  ?>
  <input class="good-edit__send" type="submit" value="Сохранить" name="good-edit-send">
  <?if(!empty($currentGood)):?>
    <input class="good-edit__send good-edit__delete" type="submit" value="Удалить" name="good-edit-delete">
  <?endif;?>
  <?
}

/**
 * Отображение всех возможных страни для пагинации
 * @param callable $getNavParams
 * @param callable $getSortParams
 */
function showFullNavigate(callable  $getNavParams, callable $getSortParams)
{
  $navParams = $getNavParams();
  $sortParams = $getSortParams();
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <?for($page = 1; $page <= $navParams['total']; $page++):?>
        <?if($page == $navParams['page']):?>
          <li class="menu-top__item menu-top__item--active"><span><?=$page?></span></li>
        <?else:?>
          <li class="menu-top__item"><a href="?page=<?=$page?>&sort=<?=$sortParams['sort_string']?>"><?=$page?></a></li>
        <?endif;?>
      <?endfor;?>
    </ul>
  </nav>
<?
}

/**
 * Отображение пагинации на основе переданного массива с данными
 * @param callable $getPagination
 */
function showNavigate(callable  $getPagination)
{
  $pagination = $getPagination();
  ?>
  <nav class="menu-top">
    <ul class="menu-top__list clearfix">
      <?$prevPage = 0;?>
      <?foreach($pagination as $page):?>
        <?if($page['PAGE']-$prevPage>1):?>
          <li class="menu-top__item"><span class="more">...</span></li>
        <?endif?>
        <?$prevPage = $page['PAGE']?>
        <?if($page['ACTIVE']):?>
          <li class="menu-top__item menu-top__item--active"><span><?=$page['PAGE']?></span></li>
        <?else:?>
          <li class="menu-top__item"><a href="<?=$page['LINK']?>"><?=$page['PAGE']?></a></li>
        <?endif;?>
      <?endforeach;?>
    </ul>
  </nav>
<?
}