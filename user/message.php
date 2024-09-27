<?php
session_start();
include("../connection/connection.php");

// Fetch user details
require __DIR__ . '/partials/fetch_user_details.php';
$user_details = get_user_info($_COOKIE["email"], $con);

// Redirect if not logged in
if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

function fetch_user_name($user_id, $con)
{
    $query = "SELECT `name` FROM `tbl_user` WHERE `id` = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['name'];
}

// Set bidder ID if passed in URL
$bidder_id = isset($_GET['bidder_id']) ? (int)$_GET['bidder_id'] : 0;

// APPWRITE - Set up client and services
require_once realpath(__DIR__ . '/../vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Databases;
use Appwrite\ID;
use Appwrite\Query;

$client = new Client();
$client->setEndpoint('https://cloud.appwrite.io/v1')
    ->setProject($_ENV["project_id"])
    ->setKey($_ENV["api_key"]);

$databases = new Databases($client);
?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php"; ?>
    <style>
        .chat-container {
            display: flex;
            height: 80vh;
            position: relative;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .chat-sidebar {
            width: 30%;
            background-color: #f8f9fa;
            overflow-y: auto;
            border-right: 1px solid #ddd;
        }

        .chat-main {
            width: 70%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: white;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #fff;
        }

        .chat-input {
            padding: 10px;
            border-top: 1px solid #ddd;
            background-color: #f1f1f1;
        }

        .chat-message {
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .chat-message.sent {
            background-color: #DCF8C6;
            align-self: flex-end;
        }

        .chat-message.received {
            background-color: #EDEDED;
            align-self: flex-start;
        }

        .user-list-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .user-list-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar-wrapper">
            <?php include("./partials/sidebar.php"); ?>
        </div>

        <div class="page-wrapper">
            <div class="page-content">
                <div class="chat-container">
                    <div class="chat-sidebar">
                        <h4 class="p-3">Recent Chats</h4>
                        <div id="userList"></div>
                    </div>

                    <div class="chat-main">
                        <div id="chatMessages" class="chat-messages"></div>
                        <div class="chat-input">
                            <form id="chatForm">
                                <input type="hidden" name="contractor_id" id="contractor_id">
                                <input type="hidden" name="client_id" value="<?php echo $user_details['id']; ?>">
                                <textarea name="chatMessage" id="chatMessage" rows="2" class="form-control"
                                    placeholder="Type a message" disabled></textarea>
                                <button type="submit" class="btn btn-primary mt-2" disabled>Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "./partials/last_code.php"; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const client_id = <?php echo $user_details['id']; ?>;
            const contractorIdFromUrl = <?php echo $bidder_id; ?>;
            loadRecentChats();

            if (contractorIdFromUrl) {
                loadMessages(contractorIdFromUrl);
                enableChat(contractorIdFromUrl);
            }

            function enableChat(selectedContractorId) {
                $('#contractor_id').val(selectedContractorId);
                $('#chatMessage').prop('disabled', false);
                $('#chatForm button[type=submit]').prop('disabled', false);
            }

            $('#chatForm').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                $.ajax({
                    url: '',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#chatMessage').val('');
                            loadMessages($('#contractor_id').val());
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            function loadRecentChats() {
                $.get('', {
                    action: 'fetch_users'
                }, function(data) {
                    $('#userList').html(data);

                    $('.user-list-item').click(function() {
                        const selectedContractorId = $(this).data('contractor-id');
                        enableChat(selectedContractorId);
                        loadMessages(selectedContractorId);
                    });
                });
            }

            function loadMessages(contractorId) {
                $.get('', {
                    action: 'fetch_messages',
                    contractor_id: contractorId
                }, function(data) {
                    $('#chatMessages').html(data);
                    scrollChatToBottom();
                });
            }

            function scrollChatToBottom() {
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });

        // PHP in one file handling actions
        <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["chatMessage"])): ?>
            <?php
            $message = htmlspecialchars(trim($_POST["chatMessage"]));
            $contractor_id = (int)$_POST["contractor_id"];
            $client_id = (int)$_POST["client_id"];

            if (!empty($message)) {
                $databases->createDocument($_ENV["database_id"], $_ENV["collection_id"], ID::unique(), [
                    "message" => $message,
                    "sender_id" => $client_id,
                    "receiver_id" => $contractor_id,
                    "timestamp" => date("Y-m-d H:i:s")
                ]);

                echo json_encode(["success" => true, "message" => "Message sent"]);
            } else {
                echo json_encode(["success" => false, "message" => "Message cannot be empty!"]);
            }

            ?>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'fetch_users'): ?>
            <?php
            $id = (int) $user_details["id"];
            $messages = $databases->listDocuments($_ENV["database_id"], $_ENV["collection_id"], [
                Query::orderDesc("timestamp"),
                Query::or([Query::equal("receiver_id", $id), Query::equal("sender_id", $id)])
            ]);

            $fetchedUsers = [];
            foreach ($messages["documents"] as $document) {
                $other_user_id = ($document["sender_id"] == $id) ? $document["receiver_id"] : $document["sender_id"];
                if (!in_array($other_user_id, $fetchedUsers)) {
                    $fetchedUsers[] = $other_user_id;
                    $user_name = fetch_user_name($other_user_id, $con); // Assuming a helper function fetch_user_name

                    echo '<div class="user-list-item" data-contractor-id="' . $other_user_id . '">' .
                        '<p class="mb-1">' . htmlspecialchars($user_name) . '</p>' .
                        '</div>';
                }
            }

            ?>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] === 'fetch_messages' && isset($_GET['contractor_id'])): ?>
            <?php
            $contractor_id = (int) $_GET["contractor_id"];
            $user_id = (int) $user_details["id"];

            $messages = $databases->listDocuments($_ENV["database_id"], $_ENV["collection_id"], [
                Query::equal("sender_id", [$user_id, $contractor_id]),
                Query::equal("receiver_id", [$user_id, $contractor_id]),
                Query::orderAsc("timestamp")
            ]);

            foreach ($messages["documents"] as $message) {
                $message_content = htmlspecialchars($message["message"]);
                $sender_id = (int) $message["sender_id"];
                $message_class = ($sender_id == $user_id) ? 'sent' : 'received';

                echo '<div class="chat-message ' . $message_class . '">' .
                    '<p>' . $message_content . '</p>' .
                    '</div>';
            }
            ?>

        <?php endif; ?>
    </script>
</body>

</html>