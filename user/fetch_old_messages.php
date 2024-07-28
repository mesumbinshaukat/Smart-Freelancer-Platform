<?php

include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

$user_details = get_user_info($_COOKIE["email"], $con);

// Check if contractor_id is set
if (!isset($_GET['contractor_id']) || empty($_GET['contractor_id'])) {
    echo "No contractor ID provided!";
    exit();
}

$contractor_id = intval($_GET['contractor_id']);

// APPWRITE
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\Query;

$client = new Client();

$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
    ->setProject($_ENV["project_id"]) // Your project ID
    ->setKey($_ENV["api_key"]); // Your secret API key

$databases = new Databases($client);

try {
    $messages = $databases->listDocuments(
        $_ENV["database_id"],
        $_ENV["collection_id"],
        [
            Query::equal("sender_id", [$user_details["id"], $contractor_id]),
            Query::equal("receiver_id", [$user_details["id"], $contractor_id]),
            Query::orderAsc("timestamp"), // Sort by timestamp in ascending order
        ]
    );

    foreach ($messages["documents"] as $message) {
        $sender_id = $message["sender_id"];
        $timestamp = $message["timestamp"];
        $message_content = empty($message["message"]) ? null : htmlspecialchars($message["message"]);
        $attachments = empty($message["attachments"]) ? null : json_decode($message["attachments"], true);

        if ($sender_id == $user_details["id"]) {
            echo "<div class='d-flex flex-row justify-content-end mb-4'>";
            echo "<div class='chat-message me-3 bg-body-tertiary border' id='senderMsg'>";
            echo "<p class='small mb-0'>" . $message_content . "</p>";
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    echo "<a href='" . htmlspecialchars($attachment) . "'>Attachment</a><br>";
                }
            }
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='d-flex flex-row justify-content-start mb-4'>";
            echo "<div class='chat-message ms-3' style='background-color: rgba(57, 192, 237, 0.2);'>";
            echo "<p class='small mb-0'>" . $message_content . "</p>";
            if (!empty($attachments)) {
                foreach ($attachments as $attachment) {
                    echo "<a href='" . htmlspecialchars($attachment) . "'>Attachment</a><br>";
                }
            }
            echo "</div>";
            echo "</div>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
