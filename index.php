<?php

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live Chat Modal</title>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

  <!-- Live Chat Button -->
  <button id="liveChatButton">Live Chat</button>

  <!-- Modal -->
  <div id="chatModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Live Chat</h2>
      <div class="chat-container">
        <div class="chat-box">
          <!-- Chat messages will go here -->
        </div>
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendButton">Send</button>
      </div>
    </div>
  </div>

  <script src="assets/js/app.js"></script>
</body>

</html>