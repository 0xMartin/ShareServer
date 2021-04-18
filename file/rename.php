<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>File sharing [RENAME]</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="../main.css" rel="stylesheet">
  </head>

  <body>
    <div class="bg-animation-container text-center">

      <!--navigation-->
      <div class="cover-container d-flex h-100 w-75 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">
          <div class="inner">
            <h3 class="masthead-brand mx-4">Share server</h3>
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
            <i class="fas fa-pencil-alt pr-3"></i>
            <span>RENAME FILE</span>
          </h2>
          <div class="border border-secondary rounded p-4">
            <form method="post">
              <div class="form-group">
                <label for="fileName">Current file name:</label>
                <?php
                  $back = explode("/", $_GET['file']);
                  echo '<input type="text" class="form-control" name="fileNameOld" value="'.array_pop($back).'" readonly>';
                ?>
              </div>
              <div class="form-group">
                <label for="fileName">New file name:</label>
                <input type="text" class="form-control" name="fileName" placeholder="Enter name of new file"></input>
                <small class="form-text text-muted">Do not use special symbols, only basic characters</small>
              </div>
              <button type="submit" class="btn btn-dark border border-secondary">
                <i class="fas fa-pencil-alt pr-2"></i>
                <span>RENAME</span>
              </button>
              <?php
                $back = explode("/", $_GET['file']);
                array_pop($back);
                echo '<button type="button" id="back" class="btn btn-dark border border-secondary" onclick="window.location.href=\'index.php?dir=';
                echo join("/", $back);
                echo '\';">';
              ?>
                <i class="fas fa-long-arrow-alt-left pr-2"></i>
                <span>BACK</span>
              </button>
            </form>
          </div>

          <div class="mt-3">
            <?php
              include 'config.php';

              $url = explode("/", $_GET['file']);
              array_pop($url);
              $path_dir = $files_dir."/".join("/", $url);

              #rename file
              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['fileName']) && isset($_POST['fileNameOld'])) {
                  $pathNew = $path_dir.'/'.$_POST['fileName'];
                  $pathOld = $path_dir.'/'.$_POST['fileNameOld'];
                  echo $pathOld;
                  if(file_exists($pathNew)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'File with this name is already on the disk';
                    echo '</div>';
                  } else if(!file_exists($pathOld)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    echo 'File to be renamed was not found';
                    echo '</div>';
                  } else {
                    rename($pathOld, $pathNew);
                    $back = explode("/", $_GET['file']);
                    array_pop($back);
                    header("Location: index.php?dir=".join("/", $back));
                  }
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
  </body>

</html>
