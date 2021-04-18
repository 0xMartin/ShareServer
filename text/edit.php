<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Text sharing [EDIT]</title>
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
            <i class="fas fa-edit pr-3"></i>
            <span>EDIT FILE</span>
          </h2>
          <div class="border border-secondary rounded p-2">
            <p><b>File name:</b> <?php echo $_GET['file']; ?></p>
            <form method="post">
              <div class="form-group">
                <?php
                  include 'config.php';

                  #write data to file
                  if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_GET["file"])) {
                      $path = $files_dir.'/'.$_GET["file"];
                        if (isset($_POST["content"])) {
                        $file = fopen($path, "w") or die("Unable to open file!");
                        fwrite($file, $_POST['content']);
                        fclose($file);
                      }
                    }
                  }


                  $create = true;
                  #read from file
                  if (isset($_GET["file"])) {
                    $path = $files_dir.'/'.$_GET["file"];
                    if(file_exists($path) && strlen($_GET["file"]) != 0) {
                      $file = fopen($path, "r") or die("Unable to open file!");
                      echo '<textarea class="form-control txt" id="content" name="content" rows="16">';
                      echo fread($file, filesize($path));
                      fclose($file);
                      echo '</textarea>';
                      $create = false;
                    }
                  }
                  #if file not exitst then create new
                  if($create) {
                    header("Location: create_file.php");
                  }
                ?>
              </div>

              <!--Buttons-->
              <div class="row">
                <div class="col-md-4 mt-1">
                  <button type="submit" class="btn btn-dark border border-secondary w-100 h-100">
                    <i class="fas fa-save pr-2"></i>
                    <span>SAVE</span>
                  </button>
                </div>
                <div class="col-md-4 mt-1">
                  <button type="button" id="refresh" class="btn btn-dark border border-secondary w-100 h-100">
                    <i class="fas fa-sync-alt pr-2"></i>
                    <span>REFRESH</span>
                  </button>
                </div>
                <div class="col-md-4 mt-1">
                  <button type="button" id="clear" class="btn btn-dark border border-secondary w-100 h-100">
                    <i class="fas fa-backspace pr-2"></i>
                    <span>CLEAR</span>
                  </button>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mt-1">
                  <button type="button" id="back" class="btn btn-dark border border-secondary w-100 h-100">
                    <i class="fas fa-long-arrow-alt-left pr-2"></i>
                    <span>BACK</span>
                  </button>
                </div>
              </div>
            </form>
          </div>

        </main>

        <footer class="mastfoot mt-auto">
          <div class="inner">
            <p>Share server, Created by <a href="mailto: martin.krcma1@gmail.com">Martin Krčma</a> © 2021, <a target="_blank" href="https://github.com/0xMartin">github</a>.</p>
          </div>
        </footer>
      </div>

    </div>
    <script src="../js/bg_animation.js"></script>
    <script src="./js/edit.js"></script>
    <script src="./js/back_btn.js"></script>
  </body>

</html>
