<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>File sharing [UPLOAD]</title>
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
          <i class="fas fa-upload pr-3"></i>
          <span>UPLOAD FILE</span>
        </h2>
        <div class="border border-secondary rounded p-2">

          <!--Dialog-->
          <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <label for="files[]">Files:</label>
              <input type="file" class="form-control bg-dark" name="files[]" multiple>
              <small class="form-text text-muted">Select file from your divice</small>
            </div>
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="ag_folder" value="1">
              <label class="form-check-label" for="ag_folder">Upload into the auto generated folder for today's date</label>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-dark border border-secondary">
                <i class="fas fa-arrow-circle-up pr-2"></i>
                <span>UPLOAD</span>
              </button>
              <button type="button" id="back" class="btn btn-dark border border-secondary">
                <i class="fas fa-long-arrow-alt-left pr-2"></i>
                <span>BACK</span>
              </button>
            </div>
          </form>
        </div>


        <div class="mt-3">
          <?php
            #upload selected files on server
            include 'config.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $conf = fopen($config_file, "r");
              $enabled = explode("=", fread($conf, filesize($config_file)))[1];
              fclose($conf);
              if(strcmp($enabled, "True") != 1) {
                echo '<div class="alert alert-danger" role="alert">';
                echo 'File uploading is disabled';
                echo '</div>';
              } else if(isset($_FILES['files'])){
                $cnt = count($_FILES['files']['name']);
                if($_FILES['files']['name'][0] === "" and $cnt == 1) {
                  echo '<div class="alert alert-danger" role="alert">';
                  echo 'You didnt select any file';
                  echo '</div>';
                  exit();
                }
                for($i=0; $i < $cnt; $i++) {
                  $errors = array();
                  $file_name = $_FILES['files']['name'][$i];
                  $file_size =$_FILES['files']['size'][$i];
                  $file_tmp =$_FILES['files']['tmp_name'][$i];
                  $file_type=$_FILES['files']['type'][$i];
                  if($file_size > 104857600){
                    $errors[]='File '.$file_name.' size must be excately 100 MB';
                  }
                  if($file_tmp === "") {
                    $errors[] = "Unable to upload file ".$file_name;
                  }
                  if($file_size == 0){
                    $errors[]='Size of file '.$file_name.' cant be  0 B ';
                  }

                  if(isset($_POST['ag_folder'])) {
                      $dir = $files_dir."/".date("Y-m-d");
                  } else {
                    $dir = $files_dir."/".$_GET['dir'];
                  }

                  if(file_exists($dir."/".$file_name)) {
                    $errors[] = 'File '.$file_name.' already exists';
                  }

                  if(empty($errors) == true){
                    if(file_exists($dir) != 1) {
                      mkdir($dir, 0700);
                    }
                    move_uploaded_file($file_tmp, $dir."/".$file_name);
                    echo '<div class="alert alert-success" role="alert">';
                    echo "File ".$file_name." successfully uploaded";
                    echo '</div>';
                  }else{
                     echo '<div class="alert alert-danger" role="alert">';
                     echo implode("<br>",$errors);
                     echo '</div>';
                  }
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
  <script src="./js/upload.js"></script>
</body>

</html>
