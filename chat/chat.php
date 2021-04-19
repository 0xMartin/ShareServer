<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Chat</title>
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
    <div class="container h-100">

      <!--Content-->
      <?php
        include 'driver/user_check.php';

        echo "<input id='chat_id' value='" . $_GET['chat'] . "' hidden></input>";
      ?>

        <div class="border border-secondary rounded p-2 mt-5">

  				<div class="card">
  					<div class="card-header msg_head">
  						<div class="d-flex bd-highlight">
  							<div class="img_cont">
                  <?php
                    if($_GET['chat'] == '0') {
                      echo '<img src="./profile/default.png" class="rounded-circle user_img">';
                    } else {
                      include 'driver/db_connect.php';
                      $users = $conn->query("SELECT Users.id, Users.name FROM ChatList LEFT JOIN Users on Users.id=ChatList.user_id WHERE ChatList.user_id!='" . $_GET['id'] . "' AND ChatList.chat_id='" . $_GET['chat'] . "';");
                      if($user = $users->fetch_assoc()) {
                        echo '<img src="./profile/' . $user['id'] . '.png" class="rounded-circle user_img">';
                      }
                    }
                  ?>
  								<span class="online_icon"></span>
  							</div>
  							<div class="user_info">
  								<span id="chat_name"></span>
  								<p><a id="today_msg_count">0</a> Messages today</p>
  							</div>
                <div class="user_info">
  								<span id="online_users" data-toggle="tooltip" data-placement="bottom" title=""></span>
  							</div>
                <span id="opt">
                    <i id="back" class="fas fa-long-arrow-alt-left" data-toggle="tooltip" data-placement="bottom" title="Back"></i>
                    <br>
                    <i id="add" class="fas fa-plus-square" data-toggle="tooltip" data-placement="bottom" title="Add user"></i>
                </span>
  						</div>
  					</div>
  					<div id="msg_body" class="card-body msg_card_body"></div>
  					<div class="card-footer">
  						<div class="input-group">
  							<div class="input-group-append">
                  <input type="file" accept="image/*" id="img" value="" hidden>
  								<span class="input-group-text attach_btn" data-toggle="tooltip" data-placement="bottom" title="Add image">
                    <i id="img_click" class="fas fa-paperclip mr-2"></i>
                    <a>&#128512;</a>
                  </span>
  							</div>
  							<textarea id="message" class="form-control type_msg" placeholder="Type your message..."></textarea>
  							<div class="input-group-append" data-toggle="tooltip" data-placement="bottom" title="Send">
  								<span class="input-group-text send_btn"><i id="send" class="fas fa-location-arrow"></i></span>
  							</div>
  						</div>
  					</div>
  				</div>
        </div>
    </div>

  </div>
  <script src="../js/bg_animation.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="./js/img_resize.js"></script>
  <script src="./js/chat.js"></script>
</body>

</html>
