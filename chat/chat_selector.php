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
      ?>

        <div class="border border-secondary rounded p-2 mt-5">
          <div class="card-body contacts_body">
            <div class="card-header msg_head bg-dark">
             <div class="d-flex bd-highlight">
                <div class="user_info">
                  <h1>Chats</h1>
                </div>
                <div class="tools" data-toggle="tooltip" data-placement="bottom" title="Create chat">
                  <span>
                    <i id="create_chat" class="far fa-comment"></i>
                  </span>
                </div>
                <span id="logout_btn" data-toggle="tooltip" data-placement="bottom" title="Log out">
                   <i class="fas fa-sign-out-alt"></i>
                </span>
             </div>
           </div>
        	 <ui class="contacts" id="contacts_body"></ui>
        	</div>
  			</div>
    </div>

  </div>
  <script src="../js/bg_animation.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="./js/chat_selector.js"></script>
</body>

</html>
