<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../../../vendor/autoload.php';

$senderId = $_POST['LoggedUser'];

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

$client = HttpClient::create();
$cache = new FilesystemAdapter(); // Using filesystem cache
$cacheKey = 'recent_chats_' . $senderId;
$recentChats = [];

// try {
//     // Attempt to fetch from cache
//     $cachedData = $cache->getItem($cacheKey);

//     if (!$cachedData->isHit()) {
//         // Make a GET request to fetch chat data
//         $response = $client->request('GET', 'https://api.pharmacollege.lk/chats');
//         $recentChats = $response->toArray();

//         // Save the response to cache for 10 minutes
//         $cachedData->set($recentChats);
//         $cachedData->expiresAfter(300); // Cache expires after 5 minutes
//         $cache->save($cachedData);
//     } else {
//         // Use cached data
//         $recentChats = $cachedData->get();
//     }
// } catch (\Exception $e) {
//     // // Handle any errors that might occur during the request
//     // echo 'Error: ' . $e->getMessage();
//     // exit;
// }
$response = $client->request('GET', 'https://api.pharmacollege.lk/chats');
$recentChats = $response->toArray();

?>

<div class="row g-3">
    <div class="col-12">
        <h5 class="table-title mb-4">Select Chat to Open</h5>
        <div class="row g-3">
            <?php foreach ($recentChats as $index => $chat) :
            ?>
                <div onclick="OpenChat('<?= $chat['chat_id'] ?>')" class="clickable chat-card d-flex align-items-center p-3 <?= ($index < count($recentChats) - 1) ? 'border-bottom' : '' ?>">
                    <div class="chat-img position-relative">
                        <img src="https://eu.ui-avatars.com/api/?name=<?= urlencode($chat['user_name']) ?>&size=50" alt="<?= $chat['user_name']; ?>" class="rounded-circle" width="50" height="50">
                        <!-- Online Status Indicator -->
                        <?php if ($chat['online_status']) : ?>
                            <span class="online-status"></span>
                        <?php endif; ?>
                    </div>
                    <div class="chat-details flex-grow-1 mx-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 <?= $chat['unread_count'] > 0 ? 'font-weight-bold' : '' ?>"><?= $chat['created_by']; ?> - <?= $chat['user_name']; ?></h5>
                            <span class="text-muted small"><?= $chat['last_message_time']; ?></span>
                        </div>
                        <p class="mb-0 text-muted <?= $chat['unread_count'] > 0 ? 'font-weight-bold' : '' ?> chat-last-message">
                            <?= $chat['last_message']; ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>