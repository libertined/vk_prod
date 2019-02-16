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

  if ($tmpFile['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($tmpFile['tmp_name'])) {
    return null;
  }

  $fInfo = finfo_open(FILEINFO_MIME);
  $mime = (string) finfo_file($fInfo, $tmpFile['tmp_name']);

  if(!$fInfo || !\App\Util\isExistArrayElementInStr($mime, $config['image_types'])) {
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

  $filePath = $_FILES[$name]['tmp_name'];

  $fileName = generate_image_name();

  $config = \Config\getSettings()['general'];
  $fileName['full_dir'] = $config["image_full_path"].$fileName['dir'];

  if(!is_dir($fileName["full_dir"])) {
    mkdir($fileName["full_dir"], 0777, true);
  }

  $imageInfo = getimagesize($filePath);
  $extension = image_type_to_extension($imageInfo[2]);

  $imagePath = sprintf("%s/%s.%s", $fileName["full_dir"], $fileName["name"], $extension);
  $savePath = sprintf("%s/%s.%s", $fileName["dir"], $fileName["name"], $extension);

  if (move_uploaded_file($filePath, $imagePath)) return $savePath;
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