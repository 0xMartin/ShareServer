//##############################################################################
// CONFIG
//##############################################################################

//response time
const response = 150;
const img_width = 1920;
const img_height = 1080;

//img name
var url = new URL(window.location.href);
var img_name = url.searchParams.get("file");
if(img_name === null) {
    img_name = "default";
}

//client id
const client_id = Math.random().toString(36).substring(7);

//##############################################################################
// CONTROLLERS
//##############################################################################

//control
var in_control_panel = false;
const control_panel = document.querySelector("#control-panel");
const span_online = document.querySelector("#online ");
const input_color = document.querySelector("#color");
const input_size = document.querySelector("#size");
const btn_clear = document.querySelector("#clear");
const btn_pen = document.querySelector("#pen");
const btn_eraser = document.querySelector("#eraser");
const btn_fill = document.querySelector("#fill");

input_color.onclick = function () {
    in_control_panel =  true;
};
input_size.onclick = function () {
    in_control_panel =  true;
};
btn_pen.onclick = function () {
    toolChange("pen");
    in_control_panel =  true;
};
btn_eraser.onclick = function () {
    toolChange("eraser");
    in_control_panel =  true;
};
btn_fill.onclick = function () {
    toolChange("fill");
    in_control_panel =  true;
};
btn_clear.onclick = function () {
    drawing = true;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawing = false;
    sendImgOnServer(true);
    in_control_panel =  true;
};

//tool
var tool = "pen";
function toolChange(btn) {
  tool = btn;
  btn_pen.classList.remove("btn-dark");
  btn_eraser.classList.remove("btn-dark");
  btn_fill.classList.remove("btn-dark");
  btn_pen.classList.remove("btn-success");
  btn_eraser.classList.remove("btn-success");
  btn_fill.classList.remove("btn-success");
  switch (btn) {
    case "pen":
      btn_pen.classList.add("btn-success");
      btn_eraser.classList.add("btn-dark");
      btn_fill.classList.add("btn-dark");
      break;
    case "eraser":
      btn_eraser.classList.add("btn-success");
      btn_pen.classList.add("btn-dark");
      btn_fill.classList.add("btn-dark");
      break;
    case "fill":
      btn_fill.classList.add("btn-success");
      btn_pen.classList.add("btn-dark");
      btn_eraser.classList.add("btn-dark");
      break;
  }
}

//##############################################################################
// CANVAS INIT
//##############################################################################

//create canvas element and append it to document body
var canvas = document.createElement('canvas');
document.querySelector("#canvas").appendChild(canvas);

document.body.style.margin = 0;
canvas.style.width = img_width;
canvas.style.height = img_height;

//drawing context
var ctx = canvas.getContext('2d');
resize();

var pos = { x: 0, y: 0 };

window.addEventListener('resize', resize);
document.addEventListener('mousemove', (e) => {
  if(!in_control_panel) {
    drawMove(e);
  } else {
    setPosition(e);
    in_control_panel = false;
  }
});
document.addEventListener('mousedown', (e) => {
  if(!in_control_panel) {
    drawClick(e);
  } else {
    setPosition(e);
    in_control_panel = false;
  }
});
document.addEventListener('mousedown', setPosition);
document.addEventListener('mouseenter', setPosition);

//##############################################################################
// DRAWING
//##############################################################################

function setPosition(e) {
  const offset = getOffset(canvas);
  pos.x = e.clientX - offset.left;
  pos.y = e.clientY - offset.top;
}

function getOffset(el) {
  const rect = el.getBoundingClientRect();
  return {
    left: rect.left + window.scrollX,
    top: rect.top + window.scrollY
  };
}

function resize() {
  ctx.canvas.width = img_width;
  ctx.canvas.height = img_height;
  last_update_time = "require_update";
}

var drawing = false;
function drawMove(e) {
  if (e.buttons !== 1) return;

  switch (tool) {
    case "pen":
      drawing = true;
      drawLine(e);
      break;
    case "eraser":
      drawing = true;
      drawEraser(e);
      break;
    default:
      return;
  }

  //send img on server (with time out)
  if(!send) {
    send = true;
    setTimeout(() => sendImgOnServer(false), response);
  }

  drawing = false;
}

function drawClick(e) {
  if (e.buttons !== 1) return;
  if(e.clientY <= control_panel.offsetHeight) return;

  switch (tool) {
    case "fill":
      drawing = true;
      drawFill(e);
      break;
    default:
      return;
  }

  //send img on server (with time out)
  if(!send) {
    send = true;
    setTimeout(() => sendImgOnServer(false), response);
  }

  drawing = false;
}

function drawLine(e) {
  ctx.beginPath();
  ctx.lineWidth = input_size.value;
  ctx.lineCap = 'round';
  ctx.strokeStyle = input_color.value;
  //draw
  ctx.moveTo(pos.x, pos.y);
  setPosition(e);
  ctx.lineTo(pos.x, pos.y);
  ctx.stroke();
}

function drawEraser(e) {
  ctx.beginPath();
  ctx.lineWidth = 100;
  ctx.lineCap = 'round';
  ctx.strokeStyle = '#ffffff';
  //draw
  ctx.moveTo(pos.x, pos.y);
  setPosition(e);
  ctx.lineTo(pos.x, pos.y);
  ctx.stroke();
}

function drawFill(e) {
  var x = e.clientX;
  var y = e.clientY;
  var imageData = ctx.getImageData(0, 0, ctx.canvas.width, ctx.canvas.height);
  var index = 4 * (x + y * imageData.width);
  var origin = [imageData.data[index + 0],
                imageData.data[index + 1],
                imageData.data[index + 2],
                imageData.data[index + 3]];
  var rgb = hexToRgb(input_color.value);
  floodFill(imageData, x, y, [rgb.r, rgb.g, rgb.b, 255], origin);
  ctx.putImageData(imageData, 0, 0);
}

function hexToRgb(hex) {
  var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
  return result ? {
    r: parseInt(result[1], 16),
    g: parseInt(result[2], 16),
    b: parseInt(result[3], 16)
  } : null;
}

function floodFill(imageData, x, y, color, origin) {
  if(origin[0] == color[0] && origin[1] == color[1] &&
    origin[2] == color[2] && origin[3] == color[3]) return;
  var queue = [];
  queue.push([x, y]);
  var p;
  var index;
  while(queue.length != 0) {
    p = queue.pop();
    if(p[0] < 0 || p[0] > ctx.canvas.width &&
       p[1] < 0 || p[1] > ctx.canvas.height) {
         continue;
    }
    index = 4 * (p[0] + p[1] * imageData.width);
    if(imageData.data[index + 0] != origin[0] ||
       imageData.data[index + 1] != origin[1] ||
       imageData.data[index + 2] != origin[2] ||
       imageData.data[index + 3] != origin[3]) {
         continue;
    }
    setPixel(imageData, p[0], p[1], color);
    queue.push([p[0] - 1, p[1]]);
    queue.push([p[0] + 1, p[1]]);
    queue.push([p[0], p[1] - 1]);
    queue.push([p[0], p[1] + 1]);
  }
}

function setPixel(imageData, x, y, color) {
    var index = 4 * (x + y * imageData.width);
    imageData.data[index + 0] = color[0];
    imageData.data[index + 1] = color[1];
    imageData.data[index + 2] = color[2];
    imageData.data[index + 3] = color[3];
}

//##############################################################################
// IMAGE SEND/RECEIVE
//##############################################################################


var send = false;
var sending = false;
function sendImgOnServer(clear_img) {
  sending = true;
  send = false;
  var dataURL = canvas.toDataURL();
  $.ajax({
      type: "POST",
      url: "driver/img_set.php",
      data: {
          id: client_id,
          imgBase64: dataURL,
          name: img_name,
          clear: clear_img
      }
  }).done(function(resp) {
      console.log("SET: " + resp);
  });
  sending = false;
}

var last_update_time = "require_update";

refresh();
function refresh() {
  $.ajax({
      type: "POST",
      url: "driver/img_get.php",
      data: {
          id: client_id,
          name: img_name,
          time: last_update_time
      }
  }).done(function(resp) {
    var data = JSON.parse(resp);
    if(!sending && !drawing) {
      switch (data.img) {
        case "clear":
          //clear
          ctx.clearRect(0, 0, canvas.width, canvas.height);
          last_update_time = data.time;
          console.log("GET: clear");
          break;
        case "actual":
          console.log("GET: actual");
          break;
        case "error":
          console.log("GET: error");
          break;
        default:
          console.log("GET: update");
          //set img data
          drawing = true;
          var image = new Image();
          image.onload = function() {
            ctx.drawImage(image, 0, 0);
          };
          image.src = 'data:image/png;base64,' + data.img;
          last_update_time = data.time;
          drawing = false;
        }
        span_online.innerHTML = data.online;
      }
  });
  setTimeout(refresh, response);
}
