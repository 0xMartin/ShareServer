<?php
  include '../config.php';
  include '../../php/main.php';

  $client_id = $_POST['id'];
  $img = $_POST['imgBase64'];
  $name = $_POST['name'];
  $clear = $_POST['clear'];

  if($clear == "true") {
    $data = base64_encode("clear");
  } else {
    $img = str_replace('data:image/png;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_encode($img);
  }
  $file = '../'.$files_dir.'/'.$name;
  $success = file_put_contents($file.'.png', $data);

  if($success) {
    include 'db_connect.php';
    $result = $conn->query("SELECT * FROM ImgUpdate WHERE client_id='".$client_id."';");

    $time = getTime();
    $ip = getClientIP();
    if($result->num_rows == 0) {
      $conn->query("INSERT INTO ImgUpdate (client_id, update_time, image_name, ip) VALUES ('".$client_id."', '".$time."', '".$name."', '".$ip."');");
    } else {
      $conn->query("UPDATE ImgUpdate SET update_time='".$time."', ip='".$ip."' WHERE client_id='".$client_id."' AND image_name='".$name."';");
    }
  }

  print $success ? $file.'.png / clear: '.$clear : 'Unable to save the file.';
?>
