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
    body {
      background-color: white;
      box-shadow: 0px 0px #ffffff00 !important;
    }
    #canvas {
      overflow: scroll;
    }
  </style>
</head>

<body>
  <div class="fixed-top w-100 border borde-dark p-1 bg-secondary" id="control-panel">

    <label class="text-light" for="color">Color: </label>
    <input type="color" id="color" value="#000000">

    <label class="text-light" for="size">Size: </label>
    <input style="width: 70px" type="number" id="size" value="2" min="1" max="150">

    <button type="button" id="clear" class="btn btn-dark border border-secondary">
      <i class="fas fa-backspace pr-2"></i>
      <span>CLEAR</span>
    </button>
    <button type="button" id="back" class="btn btn-dark border border-secondary">
      <i class="fas fa-long-arrow-alt-left pr-2"></i>
      <span>BACK</span>
    </button>

    <div class="btn-group" role="group" aria-label="Basic example">
      <button type="button" id="pen" class="btn btn-success border border-secondary">
        <i class="fas fa-pen pr-2"></i>
        <span>PEN</span>
      </button>
      <button type="button" id="fill" class="btn btn-dark border border-secondary">
        <i class="fas fa-fill-drip pr-2"></i>
        <span>FILL</span>
      </button>
      <button type="button" id="eraser" class="btn btn-dark border border-secondary">
        <i class="fas fa-eraser pr-2"></i>
        <span>ERASER</span>
      </button>
    </div>

    <span>Online: <span id="online"></span></span>
  </div>
  <div id="canvas"></div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="./js/painter.js"></script>
  <script src="./js/back_btn.js"></script>
</body>

</html>
