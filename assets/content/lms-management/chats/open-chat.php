<?php
require_once '../../../../vendor/autoload.php';
$chatId = $_POST['chatId'];

use Symfony\Component\HttpClient\HttpClient;

$chatId = $_POST['chatId'];
$senderId = $_POST['LoggedUser'];

$client = HttpClient::create();
$chatInfo = [];
try {
    // Make a GET request to fetch chat data
    $response = $client->request('GET', 'https://api.pharmacollege.lk/messages/chat/' . $chatId);
    $chatResponse = $client->request('GET', 'https://api.pharmacollege.lk/chats/' . $chatId);

    // Decode the response
    $chatInfo = $response->toArray();
    $chatDetails = $chatResponse->toArray();
} catch (\Exception $e) {
    // Handle any errors that might occur during the request
    // echo 'Error: ' . $e->getMessage();
    // exit;
}

$chatName = $chatDetails['created_by'] . " - " . $chatDetails['name'];
?>

<style>
    .loading-popup {
        z-index: 999 !important;
    }
</style>
<div class="chat-container">
    <!-- Chat Header -->
    <div class="chat-header">
        <i onclick="OpenIndex()" class="fas fa-arrow-left back-btn"></i>
        <div class="user-info mx-2">
            <img src="https://eu.ui-avatars.com/api/?name=<?= $chatName ?>&size=50" alt="User Profile">
            <div>
                <h5 class="mb-0"><?= $chatName ?></h5>
                <span class="user-status">Online</span>
            </div>
        </div>
        <i class="fas fa-ellipsis-v"></i>
    </div>
    <div class="chat-body" id="chatBody" style="background-image: url('https://support.delta.chat/uploads/default/optimized/1X/768ded5ffbef90faa338761be1c5633d91cc35e3_2_654x1024.jpeg');">
        <?php foreach ($chatInfo as $message) :
            $messageClass = ($message['sender_id'] === $senderId) ? 'sent' : 'received';
            $messageText = htmlspecialchars($message['message_text'], ENT_QUOTES, 'UTF-8');
            $messageTime = date('h:i A', strtotime($message['created_at'])); // Format time as needed
        ?>
            <div class="message <?= $messageClass ?>">
                <?= nl2br($messageText) ?>
                <span class="time"><?= $messageTime ?></span>
            </div>
        <?php endforeach ?>

    </div>

    <!-- Chat Input -->
    <div class="chat-input d-flex align-items-center p-2 border-top bg-white">
        <input type="file" id="fileInput" accept="image/*" class="d-none">
        <label for="fileInput" class="btn btn-outline-secondary">
            <i class="fas fa-image"></i>
        </label>
        <textarea rows="1" placeholder="Type a message..." id="messageInput" class="form-control mx-2"></textarea>
        <button class="btn btn-primary" onclick="sendMessage('<?= $chatId ?>', '<?= $senderId ?>') ">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>