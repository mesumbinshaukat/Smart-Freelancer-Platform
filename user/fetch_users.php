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
    $related_projects_query = $databases->listDocuments(
        $_ENV["database_id"],
        $_ENV["collection_id"],
        [
            // Messages will be sorted in descending order by their timestamp
            Query::orderDesc("timestamp"),
            Query::search("receiver_id", filter_var($id, FILTER_SANITIZE_NUMBER_INT)),
            Query::search("sender_id", filter_var($id, FILTER_SANITIZE_NUMBER_INT)),
        ]
    );

    foreach ($user["documents"] as $document) {
        $select_user_query = "SELECT * FROM `tbl_user` WHERE id = '" . $document["sender_id"] . "'";
        $select->mysqli_query($con, $select_user_query);
        $user = $select->mysqli_fetch_array($select->mysqli_query($con, $select_user_query));
        echo '<ul class="list-group">

                                            <li class="list-group-item bg-dark">
                                                <a id="' . $document["receiver_id"] . '" href="javascript:;" class="list-group-item-action fs-6 bg-light">
                                                    <div class="row align-items-center">
                                                        <div class="col-6">
                                                            <p class="mb-1 text-light fs-4">' . $user["name"] . '</p>
                                                        </div>
                                                        <div class="col-6 text-end"><i class="bx bx-message-dots text-light fs-4"></i></div>

                                                    </div>


                                                </a>
                                            </li>

                                        </ul>';
    }
} catch (\Exception $e) {

    echo $e->getMessage();
}
