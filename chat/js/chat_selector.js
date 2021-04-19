
const profile_imgs_dir = "profile";
const imgs_dir = "imgs";


const log_out_btn = document.querySelector("#logout_btn");
const create_chat_btn = document.querySelector("#create_chat");
const contacts_body = document.querySelector("#contacts_body");

//user id and password sha256 (hidden)
const user_id = document.querySelector("#user_id");
const p_sha256 = document.querySelector("#p_sha256");


log_out_btn.onclick = function() {
  document.cookie = "password=''";
  window.location.href = "index.php";
};

create_chat_btn.onclick = function() {
  window.location.href = "create_chat.php?id=" + user_id.value;;
};


function addChatEntry(chat_id, user_ids, names, online) {
  contacts_body.innerHTML += '<li class="active">' +
    '<div style="cursor: pointer;" class="d-flex bd-highlight" onclick="window.location.href = \'./chat.php?id=' + user_id.value + '&chat=' + chat_id + '\';">' +
    '<div class="img_cont">' +
    '<img src="' + (chat_id != 0 ? (profile_imgs_dir + "/" + user_ids[0]) : "./profile/default") + '.png" class="rounded-circle user_img">' +
    '<span class="online_icon ' + (online ? "" : "offline") + '"></span>' +
    '</div>'+
    '<div class="user_info">' +
    '<span>' + names.join(", ") + '</span>' +
    (online ? '<p>online</p>' : '<p>offline</p>') +
    '</div>' +
    '</div>' +
    '</li>';
}


refresh();
function refresh() {
  $.ajax({
    type: "POST",
    url: "driver/get_chats.php",
    data: {
      user_id: user_id.value,
      p_sha256: p_sha256.value
    }
  }).done(function(resp) {
    var data = JSON.parse(resp);
    contacts_body.innerHTML = "";
    for(var i = 0; i < data.chats.length; ++i) {
      var names = [];
      var ids = [];
      var online = false;
      var users = data.chats[i].users;
      for(var j = 0; j < users.length; ++j) {
        names.push(users[j].name);
        ids.push(users[j].id);
        online = users[j].online == "true" ? true : online;
      }
      addChatEntry(data.chats[i].id, ids, names, online);
    }
  });
  setTimeout(refresh, 2000);
}
