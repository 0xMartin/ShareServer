<?php
  include '../config.php';
  include '../../php/main.php';
  include 'db_connect.php';

  $name = $_POST['name'];
  $client_id = $_POST['id'];
  $last_update_time = $_POST['time'];

  $result = $conn->query("SELECT * FROM ImgUpdate WHERE update_time > '".$last_update_time."' AND client_id !='".$client_id."' AND image_name='".$name."';");

  $time = getTime();

  $online = $conn->query("SELECT DISTINCT ip FROM ImgUpdate WHERE TIMEDIFF('".$time."', update_time) < '00:02:00.000' AND image_name = '".$name."'")->num_rows;
  if(!isset($online)) {
      $online = 0;
  }

  if($result->num_rows > 0 || $last_update_time == 'require_update') {
    $img_path = '../'.$files_dir."/".$name.".png";
    if(file_exists($img_path)) {
      $data = file_get_contents($img_path);
      $base64 = base64_decode($data);
      print '{"img":"'.$base64.'", "time":"'.$time.'", "online":"'.$online.'"}';
    } else {
      print '{"img":"error", "time":"'.$time.'", "online":"'.$online.'"}';
    }
  } else {
      print '{"img":"actual", "time":"'.$time.'", "online":"'.$online.'"}';
  }

?>
