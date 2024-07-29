<?php

include("../connection/connection.php");

// APPWRITE
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\ID;

$client = new Client();

$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
    ->setProject($_ENV["project_id"]) // Your project ID
    ->setKey($_ENV["api_key"]); // Your secret API key

$databases = new Databases($client);

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = isset($_POST['chatMessage']) ? trim($_POST['chatMessage']) : '';
    $contractor_id = isset($_POST['contractor_id']) ? (int)$_POST['contractor_id'] : 0;
    $client_id = isset($_POST['client_id']) ? (int)$_POST['client_id'] : 0;

    if (empty($message) && empty($_FILES['chatAttachment']['name'])) {
        $response['success'] = false;
        $response['message'] = "Message cannot be empty!";
        echo json_encode($response);
        exit();
    }

    $attachments = empty($_FILES['chatAttachment']['name']) ? null : json_encode($_FILES['chatAttachment']['name']);
    $attachments_tmp = empty($_FILES['chatAttachment']['tmp_name']) ? null : json_encode($_FILES['chatAttachment']['tmp_name']);

    function insertToDatabase($databases, $attachments_tmp, $message, $contractor_id, $client_id)
    {
        $databases->createDocument(
            $_ENV['database_id'],
            $_ENV['collection_id'],
            ID::unique(),

            [
                "attachments" => $attachments_tmp,
                "message" => $message,
                "receiver_id" => $contractor_id,
                "sender_id" => $client_id,
                "timestamp" => date("Y-m-d H:i:s")
            ]
        );
    }

    function runAllTasks($databases, $attachments_tmp, $message, $contractor_id, $client_id)
    {
        insertToDatabase($databases, $attachments_tmp, $message, $contractor_id, $client_id);
    }

    runAllTasks($databases, $attachments_tmp, $message, $contractor_id, $client_id);

    $response['success'] = true;
    $response['message'] = "Message sent successfully!";
} else {
    $response['success'] = false;
    $response['message'] = "Error sending message!";
}

echo json_encode($response);
