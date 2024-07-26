<?php
session_start();
include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

use Appwrite\Client;
use Appwrite\Services\Databases;

require_once '../vendor/autoload.php';

$client = new Client();
$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
    ->setProject('66a31105000e5d6cf43b') // Your project ID
    ->setKey('3b708513a8103b4c2d13c023143e021fe414abf4b9bea11b33c6d046775c1ddaba36fb3ec768c258a99543112637d09445d8cc404c0b4cdf8ef391fe6d0984e1276a79fa63882362d4313abec3db828839f770672af3cbfc04068515f3500145370d347a2b1fbabae054380ab2c6ec137be30c15b45f6bff00189c9b9702925c'); // Your secret API key

$databases = new Databases($client);
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
                                            //         // Insert user data into Appwrite if not already present
                                            //         $creatorId = $row['creator_id'];
                                            //         $creatorName = $row['creator_name'];
                                            //         $bidderId = $row['bidder_id'];
                                            //         $bidderName = $row['bidder_name'];
                                            //         try {
                                            //             // Check if creator exists
                                            //             $creator = $databases->listDocuments('66a3113700066de4ba03', '66a318f0002f6c7b4138', [
                                            //                 'filters' => ['id=' . $creatorId]
                                            //             ]);
                                            //             if (count($creator['documents']) === 0) {
                                            //                 // Insert creator
                                            //                 $databases->createDocument('66a3113700066de4ba03', '66a318f0002f6c7b4138', [
                                            //                     'id' => $creatorId,
                                            //                     'name' => $creatorName,
                                            //                     'email' => '' // Fill in the email if available
                                            //                 ]);
                                            //             }
                                            //             // Check if bidder exists
                                            //             $bidder = $databases->listDocuments('66a3113700066de4ba03', '66a318f0002f6c7b4138', [
                                            //                 'filters' => ['id=' . $bidderId]
                                            //             ]);
                                            //             if (count($bidder['documents']) === 0) {
                                            //                 // Insert bidder
                                            //                 $databases->createDocument('66a3113700066de4ba03', '66a318f0002f6c7b4138', [
                                            //                     'id' => $bidderId,
                                            //                     'name' => $bidderName,
                                            //                     'email' => '' // Fill in the email if available
                                            //                 ]);
                                            //             }
                                            //         } catch (Exception $e) {
                                            //             // Handle exception if any
                                            //         }

                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . " ETH</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user_details['name']) . "</td>";
                                            echo "<td><button class='btn btn-light' data-bs-toggle='modal' data-bs-target='#chatModal' onclick='initChat(" . json_encode($user_details['id']) . ", " . json_encode($row['bidder_id']) . ")'><i class='bx bx-message-dots'></i></button></td>";
                                            echo "</tr>";
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

    <!-- User List Modal -->
    <div class="modal fade" id="userListModal" tabindex="-1" aria-labelledby="userListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userListModalLabel">Previous Interactions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userList"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="chatBody"></div>
                    <textarea id="chatMessage" rows="3" placeholder="Type your message"></textarea>
                    <input type="file" id="chatAttachment">
                    <button id="sendButton">Send</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("./partials/last_code.php") ?>

    <script src="chat.js" type="module"></script>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const floatingChatButton = document.getElementById('floatingChatButton');
        if (floatingChatButton) {
            floatingChatButton.addEventListener('click', function() {
                initUserList();
                $('#userListModal').modal('show');
            });
        }
    });
    </script>
</body>

</html>