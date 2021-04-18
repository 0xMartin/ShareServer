const btn_home = document.querySelector("#nav_home");
const btn_filesharing = document.querySelector("#nav_file");
const btn_textsharing = document.querySelector("#nav_text");
const btn_drawsharing = document.querySelector("#nav_draw");
const btn_chat = document.querySelector("#nav_chat");

btn_home.onclick = function () {
  if(!btn_home.classList.contains("active")) {
    window.location.href = "/";
  }
};

btn_filesharing.onclick = function () {
  if(!btn_filesharing.classList.contains("active")) {
    window.location.href = "/file";
  }
};

btn_textsharing.onclick = function () {
  if(!btn_textsharing.classList.contains("active")) {
    window.location.href = "/text";
  }
};

btn_drawsharing.onclick = function () {
  if(!btn_drawsharing.classList.contains("active")) {
    window.location.href = "/draw";
  }
};

btn_chat.onclick = function () {
  if(!btn_chat.classList.contains("active")) {
    window.location.href = "/chat";
  }
};

const url = window.location.href.toString();
if(url.includes("/file")) {
    btn_filesharing.classList.add("active");
} else if(url.includes("/text")) {
    btn_textsharing.classList.add("active");
} else if(url.includes("/draw")) {
    btn_drawsharing.classList.add("active");
} else if(url.includes("/chat")) {
    btn_chat.classList.add("active");
} else {
    btn_home.classList.add("active");
}
