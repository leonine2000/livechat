<?php
require_once('autoload.php');
require_once realpath(__DIR__ . '/../connect.php');
require_once realpath(__DIR__ . "/../functions/functions.php");
require_once realpath(__DIR__ . '/../config.php');

class Chat
{

  public function  getModal()
  {

    /* $chat = ' <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Live Chat from php</h2>
      <div class="chat-container">
        <div class="chat-box">
          <!-- Chat messages will go here -->
        </div>
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendButton">Send</button>
      </div>
    </div>';

 */
    $chat = '
     <div class="modal-content">
<div class="chat-card">
  <div class="chat-header">
   <div class="h2">Our Conversations</div>
   <div class ="agent_profile">
   <img src="assets/images/profile/thumb.png" />
   </div>
  </div>
  <div class="chat-body overflow-scroll">
   <div class="chat-box">
          <!-- Chat messages will go here -->
        </div>
    
  </div>
  <div class="chat-footer">
    <input id="inputfield" placeholder="Type your message" type="text">
    <button class="sendmessage">Send</button>
  </div>
</div>
</div>
';

    return $chat;
  }

  public function sendMessage()
  {
    $database = new Database();
    $conn = $database->connect();
    $conversation_name = 'new conversation';
    $user_id = 121323;
    $conversation_id = generateUUID();

    if (!isset($_SESSION['conversation_id'])) {

      $insert = $database->insert($conn, 'conversations', ['unique_id', 'conversation_name',], [$conversation_id, $conversation_name]);
      //$saved_session = $database->insert($conn, 'sessions', [], []);

      if ($insert) {
        $_SESSION['conversation_id'] = $conversation_id;
        $response = array(
          "message_sent" => true,
          "message" => "new conversation started"
        );
      } else {
        $response = array(
          "message_sent" => false,
          "message" => "new conversation already started"
        );
      }
      return $response;
    } else {
      $response = array(
        "message_sent" => true,
        "message" => " conversation in progress"
      );
      return $response;
    }
  }
}
