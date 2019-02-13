<?php

/**
 * Функции для работы с загрузкой и обработкой изображений
 */

namespace App\ImageUploader;

require_once('dependences.php');
require_once($application.'/db.php');
require_once($application.'/util.php');
require_once($config.'/settings.php');

function validate($tmpFile)
{
  $config = \Config\getSettings()['general'];

  if(!in_array($tmpFile['type'], $config['image_types'])) {
    return null;
  }

  if($tmpFile['size'] > $config['image_size']) {
    return null;
  }

  return true;
}

function uploadImage($name, $deleteImage = false){
  if(empty($_FILES[$name]) || $deleteImage) {
    return null;
  }

  if(!validate($_FILES[$name])) {
    return null;
  }

  $fileName = generate_image_name();

  $config = \Config\getSettings()['general'];
  $fileName['full_dir'] = $config["image_full_path"].$fileName['dir'];

  if(!is_dir($fileName["full_dir"])) {
    mkdir($fileName["full_dir"], 0777, true);
  }

  $path_parts = pathinfo($_FILES[$name]['tmp_name'].'/'.$_FILES[$name]['name']);

  $imagePath = sprintf("%s/%s.%s", $fileName["full_dir"], $fileName["name"], $path_parts['extension']);
  $savePath = sprintf("%s/%s.%s", $fileName["dir"], $fileName["name"], $path_parts['extension']);

  if (move_uploaded_file($_FILES[$name]['tmp_name'], $imagePath)) return $savePath;
  return null;
}

function generate_image_name() {
  $name = md5(uniqid(rand(), true));
  return [
    'dir' => substr($name, 0, 3),
    'name' => substr($name, 3),
  ];
}

function deleteImage($url)
{
  if(empty($url)) {
    return;
  }

  unlink($url);
  $directory = dirname($url);
  rmdir($directory); //не пустую директорию не удалит
}