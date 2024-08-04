<?php
class Chat
{


  public function  getModal()
  {

    $chat = ' <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Live Chat from php</h2>
      <div class="chat-container">
        <div class="chat-box">
          <!-- Chat messages will go here -->
        </div>
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button id="sendButton"> <span class="startchat">Start Chat</span></button>
      </div>
    </div>';

    return $chat;
  }
}
