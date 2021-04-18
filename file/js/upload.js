const btn_back = document.querySelector('#back');

btn_back.onclick = function () {
  window.location.href = window.location.href.toString().replace("upload.php", "index.php");
};
