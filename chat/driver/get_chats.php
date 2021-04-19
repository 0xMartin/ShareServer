<?php
  include '../../php/main.php';
  include 'db_connect.php';

  $user_id = $_POST['user_id'];
  $p_sha256 = $_POST['p_sha256'];

  $time = getTime();


  //chats
  $out ='{';
  $out .='"chats":[';

  $i = 0;
  $chat_ids = $conn->query("SELECT DISTINCT chat_id FROM ChatList WHERE user_id='" . $user_id . "';");
  while($chat_id = $chat_ids->fetch_assoc()) {

    $users = $conn->query("SELECT Users.id, Users.name FROM ChatList LEFT JOIN Users on Users.id=ChatList.user_id WHERE ChatList.user_id!='" . $user_id . "' AND ChatList.chat_id='" . $chat_id['chat_id'] . "';");

    //list of users for chat
    $out .='{"id":"' . $chat_id["chat_id"] . '", "users":[';
    $j = 0;
    while($user = $users->fetch_assoc()) {
      $out .="{";
      //id
      $out .='"id":"' . $user['id'] . '",';
      //name
      $out .='"name":"' . $user['name'] . '",';
      //check online
      $online = $conn->query("SELECT * FROM Users WHERE TIMEDIFF('".$time."', last_load_time) < '00:00:15.000' AND id='" . $user['id'] . "';");
      if($online->num_rows == 0) {
          $out .='"online":"false"';
      } else {
          $out .='"online":"true"';
      }
      if($j + 1 == $users->num_rows) {
          $out .="}";
      } else {
          $out .="},";
      }
      $j = $j + 1;
    }

    if($i + 1 == $chat_ids->num_rows) {
        $out .="]}";
    } else {
        $out .="]},";
    }
    $i = $i + 1;

  }

  $out .=']';
  $out .='}';

  print $out;
?>
