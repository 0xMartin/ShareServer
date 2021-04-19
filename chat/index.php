<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Chat [LOGIN]</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link href="../main.css" rel="stylesheet">
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
          <i class="far fa-comment pr-3"></i>
          <span>LOGIN</span>
        </h2>
        <div class="p-2">
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
            <button type="submit" class="btn btn-dark border border-secondary">
              <i class="fas fa-plus-square pr-2"></i>
              <span>LOGIN</span>
            </button>
            <button type="button" class="btn btn-dark border border-secondary" onclick="register()">
              <i class="fas fa-registered pr-2"></i>
              <span>REGISTER</span>
            </button>
            <script>
                var register = function () {
                  window.location.href = "./register.php";
                }
            </script>
          </form>
        </div>

        <div class="pt-2">
          <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              login();
            }

            function login() {
              include 'config.php';
              include 'driver/db_connect.php';

              $name = $_POST['name'];
              $password = $_POST['password'];
              $password = hash('sha256', $password);

              $result = $conn->query("SELECT * FROM Users WHERE name = '" . $name . "' AND password='" . $password . "';");
              if($result->num_rows == 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Wrong username or password';
                echo '</div>';
              } else {
                $row = $result->fetch_assoc();
                setcookie("password", $row['password'], time() + 3600);
                header("Location: chat_selector.php?id=" . $row['id']);
              }
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
