<?php
  include '../config.php';
  include 'db_connect.php';
  $id = $_GET['id'];
  $result = $conn->query("SELECT * FROM Users WHERE id = '".$id."';");
  if($result->num_rows == 0) {
    echo '<button type="button" id="back" class="btn btn-dark border border-secondary">';
    echo '<i class="fas fa-long-arrow-alt-left pr-2"></i>';
    echo '<span>BACK</span>';
    echo '</button>';
    echo '<script src="./js/back_btn.js"></script>';
    die("<span class='pl-5'>Invalid user ID</span>");
  }

  echo "<input id='user_id' value='" . $id . "' hidden></input>";
  echo "<input id='p_sha256' value='" . $_COOKIE['password'] . "' hidden></input>";
?>
