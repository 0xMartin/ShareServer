<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Draw sharing</title>
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

    .prefix::before {
      content: attr(pvalue);
      color: black;
      font-weight: bold;
      border-radius: 2px;
      background: lightgray;
      padding: 2px 6px;
      margin-right: 10px;
    }

    .postfix::after {
      content: attr(povalue);
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

      <?php
        #parse url parametres a get $dir_path_absolute
        include 'config.php';
        $current_dir_path = '/';
        if(isset($_GET['dir'])) {
          if(strlen($_GET['dir']) > 1) {
            if(strpos($_GET['dir'], "..") !== false) {
              echo '<div class="alert alert-danger" role="alert">';
              echo '".." is not allowed in directory path';
              echo '</div>';
              exit();
            }
          }
          if(file_exists($files_dir.'/'.$_GET['dir'])) {
            $current_dir_path = '/'.$_GET['dir'];
          }
        }
        $dir_path_absolute = $files_dir.$current_dir_path
      ?>

      <!--Content-->
      <main role="main" class="inner cover">

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>

        <h2>
          <i class="fas fa-paint-brush pr-3"></i>
          <span>DRAW SHARING</span>
        </h2>
        <div class="border border-secondary rounded p-2">
          <!--File list-->
          <div class="p-3 mb-2 bg-dark text-white">
            <small>Images</small>
          </div>
          <ul id="files" class="list-group">
            <?php
                include 'driver/db_connect.php';
                include '../php/main.php';

                $files = scandir($files_dir);
                if (empty($files)) {
                  echo '<h1 class="text-secondary text-center">Empty</h1>';
                } else {
                  foreach ($files as $file) {
                    if(is_file($files_dir.'/'.$file)) {
                      $size = human_filesize(filesize($files_dir.'/'.$file));

                      $time = getTime();
                      $img_name = removeFileType($file);
                      $online = $conn->query("SELECT DISTINCT ip FROM ImgUpdate WHERE TIMEDIFF('".$time."', update_time) < '00:02:00.000' AND image_name = '".$img_name."'")->num_rows;
                      if(!isset($online)) {
                          $online = 0;
                      }

                      echo '<button type="button" class="list-group-item list-group-item-action prefix postfix" pvalue="'.$size.'" povalue="Online '.$online.'">';
                      echo $file;
                      echo '</button>';
                    }
                  }
                }

                function human_filesize($bytes, $decimals = 2) {
                  $sz = 'BKMGTP';
                  $factor = floor((strlen($bytes) - 1) / 3);
                  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
                }
              ?>
          </ul>

          <!--Buttons-->
          <div class="row">
            <div class="col-md-6 mt-1">
              <button id="edit" class="btn btn-dark border border-secondary w-100 h-100">
                <i class="fas fa-edit pr-2"></i>
                <span>EDIT</span>
              </button>
            </div>
            <div class="col-md-6 mt-1">
              <button id="create" class="btn btn-dark border border-secondary w-100 h-100">
                <i class="fas fa-plus-square pr-2"></i>
                <span>CREATE NEW</span>
              </button>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mt-1">
              <button id="rename" class="btn btn-dark border border-secondary w-100 h-100">
                <i class="fas fa-pencil-alt pr-2"></i>
                <span>RENAME</span>
              </button>
            </div>
            <div class="col-md-6 mt-1">
              <button id="delete" class="btn btn-dark border border-secondary w-100 h-100">
                <i class="fas fa-trash pr-2"></i>
                <span>DELETE</span>
              </button>
            </div>
          </div>

        </div>

      </main>

      <!--Footer-->
      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p>Share server, Created by <a href="mailto: martin.krcma1@gmail.com">Martin Krčma</a> © 2021, <a target="_blank" href="https://github.com/0xMartin">github</a>.</p>
        </div>
      </footer>

      <!--File downloading-->
      <a href="" download id="download_link" hidden></a>
    </div>

  </div>
  <script src="../js/bg_animation.js"></script>
  <script src="./js/index.js"></script>
</body>

</html>
