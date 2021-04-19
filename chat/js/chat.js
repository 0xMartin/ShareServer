//##############################################################################
// CONFIG
//##############################################################################

const response = 500;
const profile_imgs_dir = "profile";
const imgs_dir = "imgs";


//##############################################################################
// INPUTS
//##############################################################################

const message = document.querySelector("#message");
const send = document.querySelector("#send");
const msg_body = document.querySelector("#msg_body");
const back_btn = document.querySelector("#back");
const add_btn = document.querySelector("#add");
const today_msg_count = document.querySelector("#today_msg_count");
const online_users = document.querySelector("#online_users");
const chat_name = document.querySelector("#chat_name");

//user id and password sha256 (hidden)
const user_id = document.querySelector("#user_id");
const p_sha256 = document.querySelector("#p_sha256");
const chat_id = document.querySelector("#chat_id");

//img input
const img = document.querySelector("#img");
const img_click = document.querySelector("#img_click");
img_click.onclick = function() {
  img.click();
};
img.addEventListener("change", function() {
  if (this.files.length == 0) {
    img_click.classList.remove("text-success");
  } else {
    img_click.classList.add("text-success");
  }
});


back_btn.onclick = function() {
  window.location.href = "chat_selector.php?id=" + user_id.value;
};

add_btn.onclick = function() {
  window.location.href = "add_user.php?id=" + user_id.value + "&chat=" + chat_id.value;
};

//msg length limitation
message.addEventListener('input', (e) => {
  var txt = e.target.value;
  do {
    var len = 0;
    for(var i = 0; i < txt.length; ++i) {
      if(txt.charAt(i) == '\n') {
        len += 4;
      } else {
        len += 1;
      }
    }
    //limit
    if(len > 1024) {
      txt = txt.substring(0, txt.length - 1);
    }
  } while(len > 1024);
  message.value = txt;
});


//##############################################################################
// ADD MSG TO body
//##############################################################################

var hour_offset = 0;

function timeFormat(time) {
  var date = new Date(time);
  date.setHours(date.getHours() + hour_offset);
  var h = date.getHours();
  var min = date.getMinutes();
  if (min < 10) {
    min = "0" + min;
  }
  var day = date.getDate();
  var month = date.getMonth();
  var year = date.getFullYear();

  var now = new Date();
  var out = "";
  var time_diff = now.getTime() - date.getTime();
  var day_diff = time_diff / (1000 * 3600 * 24);
  if (now.getDate() == day && now.getMonth() == month && now.getFullYear() == year) {
    out = h + ":" + min;
  } else if (day_diff < 7) {
    var weekday = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday"
    ];
    out = h + ":" + min + ", " + weekday[date.getDay()];
  } else {
    out = h + ":" + min + ", " + day + "." + month + " " + year
  }

  return out;
}

function addMessage(user_id, name, msg, img_url, time) {
  var time_str = timeFormat(time);
  if (name.length > 20) {
    name = name.substring(0, 20);
  }
  msg_body.insertAdjacentHTML(
    "beforeend",
    "<div class='d-flex justify-content-start mb-4 position-relative'>" +
    "<div class='img_cont_msg'>" +
    "<img src='./" + profile_imgs_dir + "/" + user_id + ".png' class='rounded-circle user_img_msg'>" +
    "</div>" +
    "<div class='msg_cotainer'>" +
    msg +
    (img_url != 'none' ? "<br><img src='./" + imgs_dir + "/chat" + chat_id.value + "/" + img_url + ".jpeg' class='msg-img rounded'>" : "") +
    "</div>" +
    "<span class='msg_info'><span class='mr-3'>" + name + "</span><span>" + time_str + "</span></span>" +
    "</div>"
  );
}

function addMyMessage(msg, img_url, time) {
  var time_str = timeFormat(time);
  msg_body.insertAdjacentHTML(
    "beforeend",
    "<div class='d-flex justify-content-end mb-4 position-relative'>" +
    "<div class='msg_cotainer_send'>" +
    msg +
    (img_url != 'none' ? "<br><img src='./" + imgs_dir + "/chat" + chat_id.value + "/" + img_url + ".jpeg' class='msg-img rounded'>" : "") +
    "</div>" +
    "<span class='msg_info_send'>" + time_str + "</span>" +
    "</div>"
  );
}

var last_names = "";
function refreshOnlineUsers(users, online) {
  var data = "";
  var names = "";
  for (var i = 0; i < online.length && i < 10; ++i) {
    names +=  online[i].name + (i + 1 == online.length ? "" : ", ");
    data += "<img src='./" + profile_imgs_dir + "/" + online[i].id + ".png' class='rounded-circle user_img_msg_small mr-1'>";
  }

  if(names == last_names) {
    return;
  }
  last_names = names;

  online_users.innerHTML = data;
  online_users.title = names;

  var name = "";
  for (var i = 0; i < users.length && i < 5; ++i) {
    name +=  users[i] + (i + 1 == users.length ? "" : ", ");
  }
  if(users.length > 5) {
      name += " ...";
  }
  chat_name.innerHTML = name;
}


//##############################################################################
// SEND MSG ON SERVER
//##############################################################################

function setCookie(cname, cvalue, exmin) {
  var d = new Date();
  d.setTime(d.getTime() + (exmin * 60 * 1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

send.onclick = function() {
  if (img.files.length == 0) {
    //send msg without img
    $.ajax({
      type: "POST",
      url: "driver/send_msg.php",
      data: {
        user_id: user_id.value,
        p_sha256: p_sha256.value,
        chat_id: chat_id.value,
        msg: message.value,
        img_base64: "none"
      }
    }).done(function(resp) {
      if (resp == "success") {
        message.value = "";
        setCookie("password", p_sha256.value, 60);
      }
      console.log(resp);
    });
  } else {
    //send msg with image
    getBase64(img.files[0])
    .then((img_base64) => resizeImage(img_base64, 300, 800, false)
    .then((result_base64) => {
      $.ajax({
        type: "POST",
        url: "driver/send_msg.php",
        data: {
          user_id: user_id.value,
          p_sha256: p_sha256.value,
          chat_id: chat_id.value,
          msg: message.value,
          img_base64: result_base64
        }
      }).done(function(resp) {
        if (resp == "success") {
          message.value = "";
          img.value = "";
          img_click.classList.remove("text-success");
          setCookie("password", p_sha256.value, 60);
        }
        console.log(resp);
      });
    }));
  }
};


//##############################################################################
// REFRESH LOOP
//##############################################################################

refresh();

var last_time = "require_update";

function refresh() {
  $.ajax({
    type: "POST",
    url: "driver/load_msg.php",
    data: {
      user_id: user_id.value,
      p_sha256: p_sha256.value,
      chat_id: chat_id.value,
      last_update_time: last_time
    }
  }).done(function(resp) {
    if(resp == "chat group not exists for this user"){
      window.location.href = "chat_selector.php?id=" + user_id.value;
    } else if(resp == "invalid user"){
      //invalid user -> return back to login page
      document.cookie = "password=''";
      window.location.href = "index.php";
    } else {
      //parse json date and new messages add to message body pannel
      var data = JSON.parse(resp);
      console.log("Refresh - msg count:" + data.msg.length);
      //hour offset
      var now = new Date();
      var server_time = new Date(data.time);
      hour_offset = Math.round((now.getTime() - server_time.getTime())/(1000 * 60 * 60));
      //msg
      if (data.msg.length != 0) {
        //today msg count
        today_msg_count.innerHTML = data.today_msg_count;
        //last update time
        last_time = data.time;
        //insert new message
        var msg;
        var img = false;
        for (var i = 0; i < data.msg.length; ++i) {
          msg = data.msg[i];
          img = msg.img_url !== "none" ? true : img;
          if (msg.user_id == user_id.value) {
            addMyMessage(msg.msg, msg.img_url, msg.msg_time);
          } else {
            addMessage(msg.user_id, msg.name, msg.msg, msg.img_url, msg.msg_time);
          }
        }
        //scroll down
        if(img) {
          setTimeout(() => {
            msg_body.scrollTop = msg_body.scrollHeight;
          }, 500);
        } else {
            msg_body.scrollTop = msg_body.scrollHeight;
        }
      }
      //online user
      refreshOnlineUsers(data.users, data.online);
    }
  });
  setTimeout(refresh, response);
}
