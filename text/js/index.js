const btn_create = document.querySelector("#create");
const btn_edit = document.querySelector("#edit");
const btn_rename = document.querySelector("#rename");
const btn_delete = document.querySelector("#delete");
const list_files = document.querySelector("#files");
var file = "";

list_files.onclick = function(event) {
    var target = getEventTarget(event);
    file = target.innerHTML;
};

btn_create.onclick = function () {
    action("create", file);
};

btn_edit.onclick = function () {
    action("edit", file);
};

btn_rename.onclick = function () {
    action("rename", file);
};

btn_delete.onclick = function () {
    action("delete", file);
};

function action(opt, file) {
  switch (opt) {
    case "create":
      window.location.href = "create_file.php";
      break;
    case "edit":
      if(file.length != 0) {
        window.location.href = "edit.php?file=" + file;
      }
      break;
    case "rename":
      if(file.length != 0) {
        window.location.href = "rename.php?file=" + file;
      }
      break;
    case "delete":
      if(file.length != 0) {
        window.location.href = "delete.php?file=" + file;
      }
      break;
  }
}

function getEventTarget(e) {
    e = e || window.event;
    return e.target || e.srcElement;
}
