<?php
  $servername = "localhost";
  $username = "user";
  $password = "d43Rd230Dguip778";
  $dbname = "drawsharing";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

?>
