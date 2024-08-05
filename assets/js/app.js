import enviromentVariable from "./class/Env.js";
/* document.addEventListener("DOMContentLoaded", function () {
  var modal = document.getElementById("chatModal");
  var btn = document.getElementById("liveChatButton");
  var span = document.getElementsByClassName("close")[0];
  var modalContent = document.querySelector(".modal-content");
  var sendButton = document.getElementById("sendButton");
  var chatInput = document.getElementById("chatInput");
  var chatBox = document.getElementsByClassName("chat-box")[0];

  // Function to handle HTTP requests
  async function request(url, method = "GET", body = null) {
    const headers = {
      "Content-Type": "application/json",
    };
    try {
      const response = await fetch(url, {
        method,
        headers,
        body: body ? JSON.stringify(body) : null,
      });
      return await response.json();
    } catch (error) {
      console.error("Error:", error);
    }
  }

  // Open the modal
  btn.onclick = function () {
    modal.style.display = "block";
    setTimeout(function () {
      modalContent.classList.add("show");
    }, 10); // Add slight delay for animation
  };

  // Close the modal
  span.onclick = function () {
    modalContent.classList.remove("show");
    setTimeout(function () {
      modal.style.display = "none";
    }, 300); // Delay matches the transition time in CSS
  };

  // Close the modal if the user clicks outside of it
  window.onclick = function (event) {
    if (event.target == modal) {
      modalContent.classList.remove("show");
      setTimeout(function () {
        modal.style.display = "none";
      }, 300); // Delay matches the transition time in CSS
    }
  };

  // Send message
  sendButton.addEventListener("click", function () {
    var message = chatInput.value;
    if (message) {
      var messageElement = document.createElement("div");
      messageElement.textContent = message;
      chatBox.appendChild(messageElement);
      chatInput.value = "";
      chatBox.scrollTop = chatBox.scrollHeight;

      // Send message to server
      sendMessage(message);
    }
  });

  // Send message on Enter key press
  chatInput.addEventListener("keyup", function (event) {
    if (event.key === "Enter") {
      sendButton.click();
    }
  });

  // Send a message to the server
  async function sendMessage(message) {
    let selectedChatId = Math.random(999);
    const data = {
      session_id: selectedChatId,
      sender: "User", // Adjust as necessary for the sender's identity
      message: message,
    };

    try {
      const result = await request("app.php?action=saveMessage", "POST", data);
      console.log(result); // Debugging: Check the response from the server
      if (!result || !result.success) {
        alert("Failed to send the message.");
      }
    } catch (error) {
      console.error("Error sending message:", error);
      alert("An error occurred. Please try again.");
    }
  }
});
 */

$(document).ready(function () {
  const getEnvironmentVariable = new enviromentVariable();

  ///////////////////////////////////////////Loader Function///////////////////////////////////////////////////////
  function startLoader(element) {
    let html = '<span class="loader"></span>';
    $(element).html(html);
  }

  function stopLoader(element, defaultElement) {
    let html = defaultElement;
    $(element).html(html);
  }
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  //startLoader($("#liveChatButton"));

  //////////////////////////////////handles starting up of the chat////////////////////////////////////////////////////
  $("#liveChatButton").on("click", async function () {
    const action = await getEnvironmentVariable.fetchApiKey("STARTCHAT");
    const actionUrl = await getEnvironmentVariable.fetchApiKey("ACTION_URL");
    const chatModal = $("#chatModal");
    const modalContent = $(".modal-content");

    $.ajax({
      url: actionUrl,
      method: "POST",
      dataType: "JSON",
      data: {
        action: action,
      },
      success(data) {
        if (data.status_code === 200) {
          chatModal.html(data.data);
          chatModal.show();
          setTimeout(function () {
            $(".modal-content").toggleClass("show");
          }, 10); // Add slight delay for animation
          bindSendMessageEvent();
        } else {
          alert("problem starting chat");
        }
      },
      error(e) {
        console.log(e);
      },
    });
  });
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  /////////////////////////////////////////////////////Send Message//////////////////////////////////////////////////////////

  function bindSendMessageEvent() {
    $(".sendmessage").off("click").on("click", SendMessage);
  }

  async function SendMessage() {
    const button = $(this);
    const action = await getEnvironmentVariable.fetchApiKey("SENDMESSAGE");
    const actionUrl = await getEnvironmentVariable.fetchApiKey("ACTION_URL");
    const inputField = $("#inputfield");
    if (inputField.val() === "") {
      inputField.toggleClass("empty-field", true);
      return;
    }
    startLoader(this);
    inputField.prop("disabled", true);
    button.prop("disabled", true);
    $.ajax({
      method: "POST",
      url: actionUrl,
      dataType: "JSON",
      data: {
        action: action,
        text: inputField.val(),
      },
      success(data) {
        console.log(data[0]);
        let messageElement = $("<div class='sender'></div>").html(data[0]); // Create a new div element and set its HTML content using jQuery
        $(".chat-box").append(messageElement); // Append the new element to the .chat-box using jQuery

        stopLoader(".sendmessage", "Send");
        inputField.prop("disabled", false);
        button.prop("disabled", false);
        inputField.val("");
      },
      error(e) {
        stopLoader(".sendmessage", "Send");
        inputField.prop("disabled", false);
        button.prop("disabled", false);
        console.log(e);
      },
    });
  }

  window.SendMessage = SendMessage;
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
});
