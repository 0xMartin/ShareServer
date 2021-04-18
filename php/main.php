<?php

function getClientIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip;
}

function getTime() {
  $now = DateTime::createFromFormat('U.u', microtime(true));
  return $now->format("Y-m-d H:i:s.u");
}

function removeFileType($filename) {
  if (strpos($filename, '.') !== false) {
    return substr($filename, 0 , (strrpos($filename, ".")));
  } else {
    return $filename;
  }
}

?>
