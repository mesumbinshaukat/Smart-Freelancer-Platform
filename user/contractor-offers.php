<?php
session_start();
include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

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



// BASIC PHP CODE
$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

$projects_query = "
    SELECT p.id as project_id, p.project_title, p.u_id as creator_id, u.name as creator_name, b.id as bid_id, b.bid_letter, b.bid_date, b.bid_price, b.user_id as bidder_id, bu.name as bidder_name
    FROM tbl_projects p
    JOIN tbl_bids b ON p.id = b.project_id
    JOIN tbl_user u ON p.u_id = u.id
    JOIN tbl_user bu ON b.user_id = bu.id
    WHERE p.u_id = ?
";
$stmt = $con->prepare($projects_query);
$stmt->bind_param("i", $user_details['id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>

</head>

<body>
    <div class="wrapper">
        <?php include("./partials/sidebar.php"); ?>
        <header>
            <?php include("./partials/navbar.php"); ?>
        </header>
        <div class="page-wrapper">
            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Contractor Name</th>
                                        <th>Proposal</th>
                                        <th>Offer Date</th>
                                        <th>Offer Price</th>
                                        <th>Project Name</th>
                                        <th>Project Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . " ETH</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user_details['name']) . "</td>";
                                            echo "<td><button class='btn btn-light' data-bs-toggle='modal' data-bs-target='#chatModal_" . $row['bid_id'] . "'><i class='bx bx-message-dots'></i></button></td>";
                                            echo "</tr>";

                                            echo '
    <div class="modal fade" id="chatModal_' . $row['bid_id'] . '" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Live Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container chat-container">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card chat-card">
                                    <div class="card-body chat-body chat-body-' . $row['bid_id'] . '" id="chatBody_' . $row['bid_id'] . '">
                                    </div>
                                    <div class="card-footer">
                                        <form class="chat-form" data-bid-id="' . $row['bid_id'] . '" data-contractor-id="' . $row['bidder_id'] . '">
                                            <input type="hidden" name="client_id" value="' . $user_details['id'] . '">
                                            <input type="hidden" name="contractor_id" value="' . $row['bidder_id'] . '">
                                            <div class="form-outline chat-textarea">
                                                <textarea class="form-control bg-body-tertiary" name="chatMessage" rows="3"></textarea>
                                                <label class="form-label">Type your message</label>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <input type="file" class="form-control" name="chatAttachment">
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary send-button" data-bid-id="' . $row['bid_id'] . '" data-contractor-id="' . $row['bidder_id'] . '">Send</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
';
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No offers found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Contractor Name</th>
                                        <th>Proposal</th>
                                        <th>Offer Date</th>
                                        <th>Offer Price</th>
                                        <th>Project Name</th>
                                        <th>Project Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include("./partials/last_code.php") ?>

    <!-- <script src="chat.js" type="module"></script> -->
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const floatingChatButton = document.getElementById('floatingChatButton');
            if (floatingChatButton) {
                floatingChatButton.addEventListener('click', function() {

                    $('#userListModal').modal('show');
                });
            }
        });



        $(document).ready(function() {
            $('.send-button').click(function() {
                alert("clicked");
                var contractorId = $(this).data('contractor-id');
                var chatForm = $('form[data-contractor-id="' + contractorId + '"]');
                $.ajax({
                    type: 'POST',
                    url: 'send_messages.php',
                    data: chatForm.serialize(),
                    success: function(response) {
                        console.log(response);
                        try {
                            var data = typeof response === "string" ? JSON.parse(response) :
                                response;
                            if (data.success) {
                                chatForm[0].reset();
                                fetchOldMessages(contractorId);
                            } else {
                                toastr.error(data.message);
                            }
                        } catch (err) {
                            console.log("Catch Error:" + err.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("Error:" + textStatus + " | " + errorThrown);
                    }
                });
            });

            function fetchOldMessages(contractorId) {
                $.ajax({
                    type: 'GET',
                    url: 'fetch_old_messages.php',
                    data: {
                        contractor_id: contractorId
                    },
                    success: function(response) {
                        $('#chatBody_' + contractorId).html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error:" + textStatus + " | " + errorThrown);
                    }
                });
            }

            $('body').on('show.bs.modal', '.modal', function() {
                var contractorId = $(this).find('.send-button').data('contractor-id');
                fetchOldMessages(contractorId);
            });

            function fetchUsers() {
                $.ajax({
                    type: 'GET',
                    url: 'fetch_users.php',
                    success: function(response) {
                        $("#userList").html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error:" + textStatus + " | " + errorThrown);
                    }
                });
            }

            fetchUsers();

        });
    </script>
</body>

</html>