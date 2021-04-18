const btn_refresh = document.querySelector("#refresh");
const btn_clear = document.querySelector("#clear");
const textarea_content = document.querySelector("#content");

btn_refresh.onclick = function () {
  location.reload();
};

btn_clear.onclick = function () {
  if(textarea_content !== null) {
    textarea_content.value = "";
  }
};
