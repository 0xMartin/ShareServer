<?php
  include '../../php/main.php';
  include '../config.php';
  include 'db_connect.php';


  $user_id = $_POST['user_id'];
  $p_sha256 = $_POST['p_sha256'];
  $msg = $_POST['msg'];
  $img = $_POST['img_base64'];

  if(strlen($msg) == 0 && $img == 'none') {
    die("empty message");
  }

  $result = $conn->query("SELECT * FROM User WHERE id = '" . $user_id . "' AND password='" . $p_sha256 . "';");
  if($result->num_rows == 0) {
    die("invalid user");
  }

  $img_url = "none";
  $success = true;
  if($img !== "none") {
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $img_url = uniqid();
    $file = '../'.$imgs_dir.'/'.$img_url.'.jpeg';
    if(!file_put_contents($file, $data)) {
      $success = false;
    }
  }

  $time = getTime();
  $msg = str_replace("\n", "<br>", $msg);
  if(!$conn->query("INSERT INTO Chat (user_id, msg_time, msg, img_url) VALUES ('".$user_id."', '".$time."', '".$msg."', '".$img_url."');")) {
    $success = false;
  }

  print $success ? "success" : 'error';
?>
