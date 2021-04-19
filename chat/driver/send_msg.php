<?php
  include '../../php/main.php';
  include '../config.php';
  include 'db_connect.php';


  $user_id = $_POST['user_id'];
  $p_sha256 = $_POST['p_sha256'];
  $chat_id = $_POST['chat_id'];
  $msg = $_POST['msg'];
  $img = $_POST['img_base64'];

  if(strlen($msg) == 0 && $img == 'none') {
    die("empty message");
  }

  //############################################################################
  //check user
  //############################################################################

  $result = $conn->query("SELECT * FROM Users WHERE id = '" . $user_id . "' AND password='" . $p_sha256 . "';");
  if($result->num_rows == 0) {
    die("invalid user");
  }

  $result = $conn->query("SELECT * FROM ChatList WHERE chat_id='" . $chat_id . "' AND user_id='" .$user_id . "';");
  if($result->num_rows == 0) {
    die("chat group not exists for this user $chat_id $user_id");
  }

  //############################################################################
  //processes message
  //############################################################################

  $img_url = "none";
  $success = true;
  if($img !== "none") {
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    $img_url = uniqid();
    $file = '../'.$imgs_dir.'/chat'.$chat_id.'/'.$img_url.'.jpeg';
    if(!file_put_contents($file, $data)) {
      $success = false;
    }
  }

  $time = getTime();
  $msg = str_replace("\n", "<br>", $msg);
  if(!$conn->query("INSERT INTO Chat" . $chat_id . " (user_id, msg_time, msg, img_url) VALUES ('".$user_id."', '".$time."', '".$msg."', '".$img_url."');")) {
    $success = false;
  }

  print $success ? "success" : 'error';
?>
