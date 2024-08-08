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
<span class="close">X</span>
  <div class="chat-header">
   <div class="h2">Our Conversations</div>
   <div class ="agent_profile">
   <img src="assets/images/profile/thumb.jpg" />
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

  public function sendMessage($message_text)
  {
    $database = new Database();
    $conn = $database->connect();
    $user_id = 121323;

    // Generate unique IDs
    $conversation_id = generateUUID();
    $session_chat_id = generateUUID();
    $message_id = generateUUID();
    $status_id = generateUUID();

    try {
      // Begin transaction
      $conn->begin_transaction();

      // Check if conversation_id exists in session
      if (!isset($_SESSION['conversation_id'])) {
        // Insert new conversation
        $insertConversation = $database->insert($conn, 'conversations', ['unique_id', 'conversation_name'], [$conversation_id, 'new conversation']);
        if (!$insertConversation) {
          throw new Exception('Error inserting conversation.');
        }

        // Insert new session
        $insertSession = $database->insert($conn, 'sessions', ['session_id', 'user_id', 'conversation_id'], [$session_chat_id, $user_id, $conversation_id]);
        if (!$insertSession) {
          throw new Exception('Error inserting session.');
        }

        // Set session variables
        $_SESSION['conversation_id'] = $conversation_id;
        $_SESSION['session_chat_id'] = $session_chat_id;
      } else {
        $conversation_id = $_SESSION['conversation_id'];
      }

      // Insert new message
      $saveMessage = $database->insert($conn, 'messages', ['message_id', 'conversation_id', 'user_id', 'message_text'], [$message_id, $conversation_id, $user_id, $message_text]);
      if (!$saveMessage) {
        throw new Exception('Error saving message.');
      }

      // Insert message status
      $saveMessageStatus = $database->insert($conn, 'messagestatus', ['message_id', 'user_id', 'status', 'status_id'], [$message_id, $user_id, 'sent', $status_id]);
      if (!$saveMessageStatus) {
        throw new Exception('Error saving message status.');
      }

      // Commit transaction 
      $conn->commit(); // save changes in database

      return [
        "message_sent" => true,
        "message" => isset($_SESSION['conversation_id']) ? "Conversation in progress" : "New conversation started"
      ];
    } catch (Exception $e) {
      // Rollback transaction on error
      $conn->rollback(); //unset changes

      return [
        "message_sent" => false,
        "message" => $e->getMessage()
      ];
    }
  }


  public function retrieveMessagesWithID($conversation_id)
  {
    $database = new Database();
    $conn = $database->connect();

    $table = 'Messages m';
    $cols = ['m.conversation_id'];
    $values = [$conversation_id]; // Example conversation ID
    $joinTables = ['MessageStatus ms'];
    $joinConditions = ['m.message_id = ms.message_id'];
    $addons = 'ORDER BY m.created_at ASC';

    $messages = $database->selectMultiColsWithJoin($conn, $table, $cols, $values, $joinTables, $joinConditions, $addons);

    return $messages;
  }
}
