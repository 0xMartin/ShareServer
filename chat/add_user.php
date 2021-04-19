<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Chat</title>
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

        <?php
          include 'driver/user_check.php';

          if($_GET['chat'] == '0') {
            header("Location: chat.php?id=".$_GET['id']."&chat=".$_GET['chat']);   
            exit();
          }

          echo "<input id='chat_id' value='" . $_GET['chat'] . "' hidden></input>";
        ?>

        <h2>
          <i class="far fa-comment pr-3"></i>
          <span>ADD USER</span>
        </h2>
        <div class="p-2">
          <form method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label class="form-label" for="name">User name:</label>
              <input type="text" class="form-control" name="username" placeholder="Enter user name">
              <small class="form-text text-muted">With whom do you want to write</small>
            </div>
            <button type="submit" class="btn btn-dark border border-secondary">
              <i class="fas fa-plus-square pr-2"></i>
              <span>ADD</span>
            </button>
            <button type="button" class="btn btn-dark border border-secondary" onclick="back()">
              <i class="fas fa-long-arrow-alt-left pr-2"></i>
              <span>BACK</span>
            </button>
            <script type="text/javascript">
                function back() {
                  const user_id = document.querySelector("#user_id");
                  const chat_id = document.querySelector("#chat_id");
                  window.location.href = "chat.php?id=" + user_id.value + "&chat=" + chat_id.value;
                }
            </script>
          </form>
        </div>

        <div class="pt-2">
          <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              add_user();
            }

            function add_user() {
              include 'config.php';
              include 'driver/db_connect.php';

              $name = $_POST['username'];
              $my_user_id = $_GET['id'];

              //get user id
              $result = $conn->query("SELECT id FROM Users WHERE name='" . $name . "';");
              if($result->num_rows == 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'User with this name not exists';
                echo '</div>';
                return;
              }
              $user_id = ($result->fetch_assoc())['id'];
              if($user_id == $my_user_id) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'You cant write yourself';
                echo '</div>';
                return;
              }

              //get new chat id
              $chat_id = $_GET['chat'];

	      //check if this user is not in this chat
              if($conn->query("SELECT * FROM ChatList WHERE chat_id='" . $chat_id . "' AND user_id='" . $user_id . "';")->num_rows != 0) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'User is already in this chat';
                echo '</div>';
                return;
              }

              //create chat group
              if(!$conn->query("INSERT INTO ChatList (chat_id, user_id) VALUES ('".$chat_id."', '".$user_id."');")) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'Failed to create chat';
                echo '</div>';
                return;
              }

              header("Location: chat_selector.php?id=".$_GET['id']."&chat=".$_GET['chat']);
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>
