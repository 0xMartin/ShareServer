<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Chat [REGISTER]</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="../main.css" rel="stylesheet">
  <link href="main.css" rel="stylesheet">
  <style>
    /* scollable list */
    .list-group {
      max-height: 400px;
      margin-bottom: 10px;
      overflow-x: hidden;
      overflow-y: scroll;
      -webkit-overflow-scrolling: touch;
      text-align: left !important;
    }

    .postfix::after {
      content: attr(pvalue);
      color: black;
      font-weight: bold;
      border-radius: 2px;
      background: lightgray;
      padding: 2px 6px;
      margin-left: 10px;
    }
  </style>
</head>

<body>
  <div class="bg-animation-container text-center">

    <!--navigation-->
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
      <header class="masthead mb-auto">
        <div class="inner">
          <h3 class="masthead-brand mx-4">Share server </h3>
          <nav class="nav nav-masthead justify-content-center">
            <a id="nav_home" class="nav-link">Home</a>
            <a id="nav_file" class="nav-link">File sharing</a>
            <a id="nav_text" class="nav-link">Text sharing</a>
            <a id="nav_draw" class="nav-link">Draw sharing</a>
            <a id="nav_chat" class="nav-link">Chat</a>
          </nav>
          <script src="../js/navigation.js"></script>
        </div>
      </header>

      <!--Content-->
      <main role="main" class="inner cover">

        <h2>
          <i class="fas fa-registered pr-3"></i>
          <span>REGISTER</span>
        </h2>
        <div class="border border-secondary rounded p-2">
          <form method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="form-label" for="name">User name:</label>
              <input type="text" class="form-control" name="name" placeholder="Enter your name">
              <small class="form-text text-muted">As other users will see you in the chat</small>
            </div>
            <div class="form-group">
              <label class="form-label" for="password">Password:</label>
              <input type="password" class="form-control" name="password" placeholder="Enter your password">
            </div>
            <div>
              <div class="row pb-4 pt-2">
                <div class="col">
                  <label class="form-label" for="image">Profile image (Max 2MB):</label>
                  <input type="file" class="form-control" name="image" accept="image/*" onchange="loadFile(event)">
                </div>
                <div class="col">
                  <img id="output" class="rounded-circle user_img" style="width: 100px; height: 100px;"/>
                  <script>
                    var loadFile = function(event) {
                      //display image
                      var reader = new FileReader();
                      reader.onload = function(){
                        var output = document.querySelector('#output');
                        output.src = reader.result;
                      };
                      reader.readAsDataURL(event.target.files[0]);
                    };
                  </script>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-dark border border-secondary">
              <i class="fas fa-plus-square pr-2"></i>
              <span>CREATE</span>
            </button>
            <button type="button" id="back" class="btn btn-dark border border-secondary">
              <i class="fas fa-long-arrow-alt-left pr-2"></i>
              <span>BACK</span>
            </button>
          </form>
        </div>

        <div class="pt-2">
          <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              createProfile();
            }

            function createProfile() {
              include 'config.php';
              include 'driver/db_connect.php';
              include '../php/main.php';

              $name = $_POST['name'];
              $password = $_POST['password'];

              if(!isset($_POST['name']) || strlen($name) == 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Field with username is empty';
                echo '</div>';
                return;
              }

              if(!isset($_POST['password']) || strlen($password) == 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Field with password is empty';
                echo '</div>';
                return;
              }

              if(!isset($_FILES['image'])) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'You must select profile image';
                echo '</div>';
                return;
              }

              $cnt = 0;
              do {
                $user_id = uniqid();
                $result = $conn->query("SELECT * FROM Users WHERE id = '".$user_id."';");
                $cnt = $cnt + 1;
              } while($result->num_rows > 0 && $cnt < 5000);

              if($cnt >= 5000) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'ID generating error';
                echo '</div>';
                return;
              }

              $password = hash('sha256', $password);
              if($conn->query("INSERT INTO Users (id, name, password, last_load_time) VALUES ('".$user_id."', '".$name."', '".$password."', '2000-01-01 00:00:00.000');")) {
                echo '<div class="alert alert-success" role="alert">';
                echo 'User successfully created';
                echo '</div>';
              } else {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'User with this name already exists';
                echo '</div>';
                return;
              }

              if ($_FILES['image']['size'] > 2097152) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Image exceeded maximum size';
                echo '</div>';
                return;
              }

              if (!move_uploaded_file($_FILES['image']['tmp_name'], $profile_dir . "/" . $user_id . ".png")) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Image uploading faild';
                echo '</div>';
              }

              setcookie("password", $password, time() + 3600);
              header("Location: chat.php?id=" . $user_id);
            }
          ?>
        </div>

      </main>

      <!--Footer-->
      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>Share server, Created by <a href="mailto: martin.krcma1@gmail.com">Martin Krčma</a> © 2021, <a target="_blank" href="https://github.com/0xMartin">github</a>.</p>
        </div>
      </footer>
    </div>

  </div>
  <script src="../js/bg_animation.js"></script>
  <script src="./js/back_btn.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>
