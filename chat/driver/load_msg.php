<?php
  include '../../php/main.php';
  include '../config.php';
  include 'db_connect.php';


  $user_id = $_POST['user_id'];
  $last_update_time = $_POST['last_update_time'];
  $p_sha256 = $_POST['p_sha256'];

  $time = getTime();

  //############################################################################
  //check user
  //############################################################################

  $result = $conn->query("SELECT * FROM Users WHERE id = '" . $user_id . "' AND password='" . $p_sha256 . "';");
  if($result->num_rows == 0) {
    die('invalid user');
  } else {
    $conn->query("UPDATE Users SET last_load_time='" . $time . "' WHERE id = '" . $user_id . "';");
  }


  //############################################################################
  //output
  //############################################################################
  $out = '{';

  //time
  $out .='"time":"' . $time . '",';

  //names of online users
  $out .='"online":[';
  $online_names = $conn->query("SELECT * FROM Users WHERE TIMEDIFF('".$time."', last_load_time) < '00:00:30.000'");
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
  $today_msg_count = $conn->query("SELECT * FROM Chat WHERE CAST(msg_time as Date) = CAST('" . $time . "' as Date);");
  $out .='"today_msg_count":"' . $today_msg_count->num_rows . '",';


  //load msg from db
  if($last_update_time == "require_update") {
    $result = $conn->query("SELECT * FROM Chat;");
  } else {
    $result = $conn->query("SELECT * FROM Chat WHERE msg_time > '$last_update_time';");
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
