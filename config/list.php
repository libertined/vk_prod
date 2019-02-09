<?php
/**
 * Настройки товарного листинга
 */
return [
  'gods_on_page' => 100,
  'page_code' => 'page',
  'sort_code' => 'sort',
  'default_sort' => 'price_asc',
  'sort' => [
    'price_asc' => 'price_up',
    'price_desc' => 'price_down',
    'id_asc' => 'id_up',
    'id_desc' => 'id_down',
  ],
  'menu' => [
    'edit' => [
      'LINK' => 'edit.php',
      'TITLE' => 'Добавить товар'
    ]
  ]
];