<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>File sharing [CREATE]</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="../main.css" rel="stylesheet">
  </head>

  <body class="text-center">

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
          <div class="container">
            <h2>
              <i class="fas fa-plus-square pr-3"></i>
              <span>CREATE DIR</span>
            </h2>
            <div class="border border-secondary rounded p-2">
              <form method="post">
                <div class="form-group">
                  <label for="fileName">Directory name:</label>
                  <input type="text" class="form-control" name="fileName" placeholder="Enter name of new dir">
                  <small class="form-text text-muted">Do not use special symbols, only basic characters</small>
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

            <div class="mt-3">
              <?php
                include 'config.php';

                #create file
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  if (isset($_POST['fileName'])) {
                    $path = $files_dir.'/'.$_POST['fileName'];
                    if(substr_compare($path, ".png", -strlen(".png")) !== 0) {
                        $path = $path . ".png";
                    }
                    if(file_exists($path)) {
                      echo '<div class="alert alert-danger" role="alert">';
                      echo 'Directory with this name is already on the disk';
                      echo '</div>';
                    } else {
                      $file = fopen($path, "w");
                      fclose($file);
                      header("Location: edit.php?file=".$_POST['fileName']);
                    }
                  }
                }
              ?>
            </div>
          </div>
        </main>

        <!--Footer-->
        <footer class="mastfoot mt-auto">
          <div class="inner">
            <p>Share server, Created by <a href="mailto: martin.krcma1@gmail.com">Martin Kr??ma</a> ?? 2021, <a target="_blank" href="https://github.com/0xMartin">github</a>.</p>
          </div>
        </footer>
      </div>

      <script src="./js/back_btn.js"></script>
    </body>

</html>
