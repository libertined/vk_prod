<?php
/**
 * Настройки товарного листинга
 */
return [
  'gods_on_page' => 100,
  'page_code' => 'page',
  'sort_code' => 'sort',
  'default_sort' => 'id_up',
  'sort' => [
    'price_asc' => 'price_up',
    'price_desc' => 'price_down',
    'id_asc' => 'id_up',
    'id_desc' => 'id_down',
  ],
  'menu' => [
    'edit' => [
      'LINK' => 'edit.php',
      'TITLE' => 'Добавить товар >',
      'CLASS' => 'menu-top__item--colored'
    ]
  ],
  'titles' => [
    "ID" => "ID",
    "IMG" => "Изображение",
    "TITLE" => "Название",
    "DESC" => "Описание",
    "PRICE" => "Цена",
    "ACTIONS" => ""
  ],
  'pagination' => [
    "left" => 3,
    "right" => 3,
    "showFirst" => true,
    "showLast" => true,
  ],
];