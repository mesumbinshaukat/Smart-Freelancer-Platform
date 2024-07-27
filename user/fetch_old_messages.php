<?php

include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

$user_details = get_user_info($_COOKIE["email"], $con);


// APPWRITE
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\ID;
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
            // Messages will be sorted in descending order by their timestamp
            Query::orderDesc("timestamp"),

        ]
    );

    foreach ($messages["documents"] as $message) {
        $sender_id = $message["sender_id"];
        $receiver_id = $message["receiver_id"];
        $timestamp = $message["timestamp"];
        $message = empty($message["message"]) ? null : $message["message"];
        $attachments = empty($message["attachments"]) ? null : json_decode($message["attachments"], true);


        if ($sender_id == $user_details["id"]) {

            echo "<div class='d-flex flex-row justify-content-end mb-4'>";
            echo "<div class='chat-message me-3 bg-body-tertiary border' id='senderMsg'>";
            echo "<p class='small mb-0'>" . $message . "</p>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='d-flex flex-row justify-content-start mb-4'>";
            echo "<div class='chat-message ms-3' style='background-color: rgba(57, 192, 237, 0.2);'>";
            echo "<p class='small mb-0'>" . $message . "</p>";
            echo "</div>";
            echo "</div>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
