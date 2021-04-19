<?php
  include '../../php/main.php';
  include '../config.php';
  include 'db_connect.php';


  $user_id = $_POST['user_id'];
  $last_update_time = $_POST['last_update_time'];
  $p_sha256 = $_POST['p_sha256'];
  $chat_id = $_POST['chat_id'];

  $time = getTime();

  //############################################################################
  //check user
  //############################################################################

  $result = $conn->query("SELECT * FROM Users WHERE id = '" . $user_id . "' AND password='" . $p_sha256 . "';");
  if($result->num_rows == 0) {
    die('invalid user');
  }

  $result = $conn->query("SELECT * FROM ChatList WHERE chat_id='" . $chat_id . "' AND user_id='" .$user_id . "';");
  if($result->num_rows == 0) {
    die("chat group not exists for this user");
  } else {
    $conn->query("UPDATE Users SET last_load_time='" . $time . "', active_chat_id='" . $chat_id . "' WHERE id = '" . $user_id . "';");
  }


  //############################################################################
  //output
  //############################################################################
  $out = '{';

  //time
  $out .='"time":"' . $time . '",';

  //names
  $users = $conn->query("SELECT Users.name FROM ChatList LEFT JOIN Users on Users.id=ChatList.user_id WHERE ChatList.user_id!='" . $user_id . "' AND ChatList.chat_id='" . $chat_id . "';");

  $out .='"users":[';
  $index = 0;
  while($user = $users->fetch_assoc()) {
    $out .='"' . $user['name'] . '"';
      if($index + 1 < $users->num_rows) {
        $out .=',';
      }
      $index = $index + 1;
  }
  $out .='],';

  //names of online users
  $out .='"online":[';
  $online_names = $conn->query("SELECT * FROM Users WHERE TIMEDIFF('".$time."', last_load_time) < '00:00:05.000' AND active_chat_id= '" . $chat_id . "';");
  $index = 0;
  while($row = $online_names->fetch_assoc()) {
      $out .='{"name":"' . $row["name"] . '",';
      $out .='"id":"' . $row["id"] . '"';
      if($index + 1 == $online_names->num_rows) {
        $out .='}';
      } else {
        $out .='},';
      }
    $index = $index + 1;
  }
  $out .='],';

  //msg count
  $today_msg_count = $conn->query("SELECT * FROM Chat" . $chat_id . " WHERE CAST(msg_time as Date) = CAST('" . $time . "' as Date);");
  $out .='"today_msg_count":"' . $today_msg_count->num_rows . '",';


  //load msg from db
  if($last_update_time == "require_update") {
    $result = $conn->query("SELECT * FROM Chat" . $chat_id . ";");
  } else {
    $result = $conn->query("SELECT * FROM Chat" . $chat_id . " WHERE msg_time > '" . $last_update_time . "';");
  }
  $count = $result->num_rows;

  //messages
  if ($count > 0) {
    $out .='"msg":[';
    $index = 0;
    while($row = $result->fetch_assoc()) {
      $sr = $conn->query("SELECT name FROM Users WHERE id='" . $row["user_id"] . "';");
      $name = $sr->fetch_assoc();
      $out .='{';
      $out .='"user_id":"' . $row["user_id"] . '",';
      $out .='"name":"' . $name["name"] . '",';
      $out .='"msg_time":"' . $row["msg_time"] . '",';
      $out .='"msg":"' . $row["msg"] . '",';
      $out .='"img_url":"' . $row["img_url"] . '"';
      if($index + 1 == $count) {
        $out .='}';
      } else {
        $out .='},';
      }
      $index = $index + 1;
    }
    $out .=']';
  } else {
    $out .='"msg":[]';
  }
  $out .= '}';


  print $out;
?>
