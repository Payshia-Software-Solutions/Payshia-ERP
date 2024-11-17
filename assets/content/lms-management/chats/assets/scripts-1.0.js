var UserLevel = document.getElementById("UserLevel").value;
var LoggedUser = document.getElementById("LoggedUser").value;
var company_id = document.getElementById("company_id").value;
var default_location = document.getElementById("default_location").value;
var default_location_name = document.getElementById(
  "default_location_name"
).value;

$(document).ready(function () {
  OpenIndex();
});

function OpenIndex() {
  ClosePopUP();

  function fetch_data() {
    document.getElementById("index-content").innerHTML = InnerLoader;
    $.ajax({
      url: "./assets/content/lms-management/chats/index.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        company_id: company_id,
      },
      success: function (data) {
        $("#index-content").html(data);
      },
    });
  }
  fetch_data();
}

function OpenChat(chatId) {
  OpenPopup();
  document.getElementById("loading-popup").innerHTML = InnerLoader;

  function fetch_data() {
    $.ajax({
      url: "./assets/content/lms-management/chats/open-chat.php",
      method: "POST",
      data: {
        LoggedUser: LoggedUser,
        UserLevel: UserLevel,
        chatId: chatId,
      },
      success: function (data) {
        $("#loading-popup").html(data);
      },
    });
  }
  fetch_data();
}

const API_URL = "https://api.pharmacollege.lk/";

function sendMessage(chatId, sender_id) {
  const messageInput = document.querySelector("#messageInput");
  const messageText = messageInput.value.trim();

  if (!messageText) {
    OpenAlert("error", "Oops!", "Message cannot be empty!");
    return; // Stop execution if the message is empty
  }

  axios
    .post(
      API_URL + "/messages/",
      {
        chat_id: chatId,
        sender_id: sender_id, // Set this to the current user ID
        message_text: messageText,
        message_type: "text",
        message_status: "sent",
      },
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    )
    .then((response) => {
      var input = document.getElementById("messageInput");
      var text = input.value.trim();
      if (text) {
        var message = document.createElement("div");
        message.classList.add("message", "sent");
        message.textContent = text;
        var time = document.createElement("span");
        time.classList.add("time");
        time.textContent = new Date().toLocaleTimeString().slice(0, 5);
        message.appendChild(time);
        document.getElementById("chatBody").appendChild(message);
        input.value = "";
        document.getElementById("chatBody").scrollTop =
          document.getElementById("chatBody").scrollHeight;
      }
    })
    .catch((error) => console.error("Error sending message:", error));
}
