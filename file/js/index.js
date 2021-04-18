const files_dir = "files";

const btn_download = document.querySelector("#download");
const btn_upload = document.querySelector("#upload");
const btn_createdir = document.querySelector("#createdir");
const btn_delete = document.querySelector("#delete");
const btn_rename = document.querySelector("#rename");
const btn_goin = document.querySelector("#goin");
const btn_goback = document.querySelector("#goback");

const list_files = document.querySelector("#files");
const input_selected_file = document.querySelector("#selected_file");
var selecte_dir = "";
var selecte_file = "";

btn_delete.onclick = function () {
  modificator("delete");
};

btn_rename.onclick = function () {
  modificator("rename");
};

btn_createdir.onclick = function () {
  var dir_path_absolute = getDirPathFromURL();
  window.location.href = "./create_dir.php?dir=" + dir_path_absolute;
};

function modificator(modification) {
  if(selecte_file.length == 0 && selecte_dir.length == 0) return;
  var dir_path_absolute = getDirPathFromURL();
  if(dir_path_absolute.length != 0) {
    dir_path_absolute += "/";
  }
  window.location.href = "./"+modification+".php?file=" + dir_path_absolute + (selecte_file.length != 0 ? selecte_file : selecte_dir);
};

btn_upload.onclick = function () {
  window.location.href = "./upload.php?dir=" + getDirPathFromURL();
};

btn_goin.onclick = function () {
  if(selecte_dir.length == 0) return;
  var dir_path_absolute = getDirPathFromURL();
  if(dir_path_absolute.length != 0) {
    dir_path_absolute += "/";
  }
  window.location.href = "./index.php?dir=" + dir_path_absolute + selecte_dir;
};

btn_goback.onclick = function () {
  var dir_path_absolute = getDirPathFromURL().split("/");
  dir_path_absolute.pop();
  window.location.href = "./index.php?dir=" + dir_path_absolute.join("/");
};

btn_download.onclick = function () {
    if(selecte_file.length == 0) return;
    var dir_path_absolute = getDirPathFromURL();
    if(dir_path_absolute.length != 0) {
      dir_path_absolute += "/";
    }
    var donwload_link = document.querySelector('#download_link');
    donwload_link.href = files_dir + "/" + dir_path_absolute + selecte_file;
    donwload_link.click();
};

list_files.onclick = function(event) {
    var target = getEventTarget(event);
    if(target.getAttribute("pvalue") == "Dir") {
      selecte_dir = target.innerHTML;
      selecte_file = "";
    } else {
      selecte_dir = "";
      selecte_file = target.innerHTML;
    }
};

function getEventTarget(e) {
    e = e || window.event;
    return e.target || e.srcElement;
}

function getDirPathFromURL() {
  const current_dir_path = document.querySelector("#current_dir_path");
  var dir_path_absolute = current_dir_path.innerHTML;
  if(dir_path_absolute.startsWith("/")) {
      dir_path_absolute = dir_path_absolute.substring(1);
  }
  return dir_path_absolute;
}
