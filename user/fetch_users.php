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

$id = (int) $user_details["id"];

try {
    $messages = $databases->listDocuments(
        $_ENV["database_id"],
        $_ENV["collection_id"],
        [
            Query::orderDesc("timestamp"),
            Query::or([Query::equal("receiver_id", $id), Query::equal("sender_id", $id)]),
        ]
    );

    $fetchedUsers = [];
    $userBids = []; // Store bid IDs associated with users

    // Fetch bid IDs for each user
    $bids_query = "SELECT id, user_id FROM tbl_bids";
    $bids_result = mysqli_query($con, $bids_query);
    while ($bid = mysqli_fetch_assoc($bids_result)) {
        $userBids[$bid['user_id']] = $bid['id'];
    }

    foreach ($messages["documents"] as $document) {
        $sender_id = $document["sender_id"];
        $receiver_id = $document["receiver_id"];

        if ($sender_id != $id && !in_array($sender_id, $fetchedUsers)) {
            $fetchedUsers[] = $sender_id;
            $select_user_query = "SELECT * FROM `tbl_user` WHERE `id` = '{$sender_id}'";
            $result = mysqli_query($con, $select_user_query);
            if ($user = mysqli_fetch_assoc($result)) {
                $bid_id = isset($userBids[$sender_id]) ? $userBids[$sender_id] : 0;
                echo '<ul class="list-group">
                        <li class="list-group-item bg-dark">
                            <div class="list-group-item-action fs-6 bg-dark user-item" data-contractor-id="' . $sender_id . '" data-bid-id="' . $bid_id . '">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <p class="mb-1 text-light fs-4">' . htmlspecialchars($user["name"]) . '</p>
                                    </div>
                                    <div class="col-6 text-end"><i class="bx bx-message-dots text-light fs-4"></i></div>
                                </div>
                            </div>
                        </li>
                    </ul>';
            }
        }

        if ($receiver_id != $id && !in_array($receiver_id, $fetchedUsers)) {
            $fetchedUsers[] = $receiver_id;
            $select_user_query = "SELECT * FROM `tbl_user` WHERE `id` = '{$receiver_id}'";
            $result = mysqli_query($con, $select_user_query);
            if ($user = mysqli_fetch_assoc($result)) {
                $bid_id = isset($userBids[$receiver_id]) ? $userBids[$receiver_id] : 0;
                echo '<ul class="list-group">
                        <li class="list-group-item bg-dark">
                            <div class="list-group-item-action fs-6 bg-dark user-item" data-contractor-id="' . $receiver_id . '" data-bid-id="' . $bid_id . '">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <p class="mb-1 text-light fs-4">' . htmlspecialchars($user["name"]) . '</p>
                                    </div>
                                    <div class="col-6 text-end"><i class="bx bx-message-dots text-light fs-4"></i></div>
                                </div>
                            </div>
                        </li>
                    </ul>';
            }
        }
    }
} catch (\Exception $e) {
    echo $e->getMessage();
}