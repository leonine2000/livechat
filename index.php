<?php
//require_once('config/class/autoload.php');
require_once('config/connect.php');
//echo($_SESSION['conversation_id']);
/* if (isset($_SESSION['conversation_id'])) {
  echo ($_SESSION['conversation_id']);
  echo '<br/>';
  echo ($_SESSION['session_chat_id']);
  echo '<br/>';
}
$chat = new Chat();
$messageSent = $chat->retrieveMessagesWithID('ada6036d-aeb9-4526-9f4d-443ba4fac9f4');


foreach ($messageSent['data'] as $message) {
  var_dump($message['message_text']) ;
} */
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Live Chat Modal</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

</head>

<body>
  <div class="dummy-body">

  </div>

  <!-- Live Chat Button -->
  <button id="liveChatButton"><span class="startchat">
      <ul>
        <li>
          <span class="icon">💬</span>
          <span class="title">Start Chat</span>
        </li>
      </ul>
    </span></button>

  <!-- Modal -->
  <div id="chatModal" class="modal">

  </div>

  <script type="module" src="assets/js/app.js"></script>
</body>

</html>