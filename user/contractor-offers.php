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
                                        <th>Award Project</th>
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
                                            echo "<td><button class='btn btn-primary award-project-btn' data-project-id='" . $row['project_id'] . "' data-bid-price='" . $row['bid_price'] . "' data-contractor-id='" . $row['bidder_id'] . "'>Award</button></td>";
                                            echo "<td><button class='btn btn-light chat-button' data-bs-toggle='modal' data-bs-target='#chatModal_" . $row['bid_id'] . "' data-bid-id='" . $row['bid_id'] . "' data-contractor-id='" . $row['bidder_id'] . "'><i class='bx bx-message-dots'></i></button></td>";
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
                                    <div class="card-body chat-body" id="chatBody_' . $row['bid_id'] . '">
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

    <!-- Award Project Modal -->
    <div class="modal fade" id="awardProjectModal" tabindex="-1" aria-labelledby="awardProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="awardProjectModalLabel">Award Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to award this project?</p>
                    <input type="hidden" id="modal-project-id">
                    <input type="hidden" id="modal-bid-price">
                    <input type="hidden" id="modal-contractor-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-award-btn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("./partials/last_code.php") ?>
    <script src="../node_modules/web3/dist/web3.min.js"></script>

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
            var contractorId = $(this).data('contractor-id');
            var chatForm = $('form[data-contractor-id="' + contractorId + '"]');
            var formData = new FormData(chatForm[0]);

            $.ajax({
                type: 'POST',
                url: 'send_messages.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                    try {
                        var data = typeof response === "string" ? JSON.parse(response) :
                            response;
                        if (data.success) {
                            chatForm[0].reset();
                            fetchOldMessages(contractorId, chatForm.data('bid-id'));
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

        function fetchOldMessages(contractorId, bidId) {
            $.ajax({
                type: 'GET',
                url: 'fetch_old_messages.php',
                data: {
                    contractor_id: contractorId
                },
                success: function(response) {
                    $('#chatBody_' + bidId).html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error:" + textStatus + " | " + errorThrown);
                }
            });
        }

        $('body').on('show.bs.modal', '.modal', function() {
            var contractorId = $(this).find('.send-button').data('contractor-id');
            var bidId = $(this).find('.send-button').data('bid-id');
            fetchOldMessages(contractorId, bidId);
            setInterval(function() {
                fetchOldMessages(contractorId, bidId);
            }, 500);
        });

        $('body').on('click', '.user-item', function() {
            var contractorId = $(this).data('contractor-id');
            var bidId = $(this).data('bid-id');
            var modalId = '#chatModal_' + bidId;
            $(modalId).modal('show');
            fetchOldMessages(contractorId, bidId);
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

        $('.award-project-btn').click(function() {
            $('#modal-project-id').val($(this).data('project-id'));
            $('#modal-bid-price').val($(this).data('bid-price'));
            $('#modal-contractor-id').val($(this).data('contractor-id'));
            $('#awardProjectModal').modal('show');
        });

        $('#confirm-award-btn').click(async function() {
            const projectId = $('#modal-project-id').val();
            const bidPrice = $('#modal-bid-price').val();
            const contractorId = $('#modal-contractor-id').val();

            // Close the modal
            $('#awardProjectModal').modal('hide');

            if (typeof window.ethereum !== 'undefined') {
                const web3 = new Web3(window.ethereum);

                try {
                    await window.ethereum.request({
                        method: 'eth_requestAccounts'
                    });

                    const contractAddress = "0x2bb6c037Ee8E4cc87fE1E1CFA75c515A459c4e00";
                    const contractABI = [{
                            "inputs": [{
                                    "internalType": "uint256",
                                    "name": "projectId",
                                    "type": "uint256"
                                },
                                {
                                    "internalType": "address",
                                    "name": "contractor",
                                    "type": "address"
                                }
                            ],
                            "name": "awardProject",
                            "outputs": [],
                            "stateMutability": "payable",
                            "type": "function"
                        },
                        {
                            "inputs": [{
                                "internalType": "uint256",
                                "name": "projectId",
                                "type": "uint256"
                            }],
                            "name": "completeProject",
                            "outputs": [],
                            "stateMutability": "nonpayable",
                            "type": "function"
                        },
                        {
                            "inputs": [],
                            "stateMutability": "nonpayable",
                            "type": "constructor"
                        },
                        {
                            "anonymous": false,
                            "inputs": [{
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "projectId",
                                    "type": "uint256"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "fee",
                                    "type": "uint256"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "netAmount",
                                    "type": "uint256"
                                }
                            ],
                            "name": "FeeDeducted",
                            "type": "event"
                        },
                        {
                            "anonymous": false,
                            "inputs": [{
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "projectId",
                                    "type": "uint256"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "address",
                                    "name": "contractor",
                                    "type": "address"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "amount",
                                    "type": "uint256"
                                }
                            ],
                            "name": "ProjectAwarded",
                            "type": "event"
                        },
                        {
                            "anonymous": false,
                            "inputs": [{
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "projectId",
                                    "type": "uint256"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "address",
                                    "name": "contractor",
                                    "type": "address"
                                },
                                {
                                    "indexed": false,
                                    "internalType": "uint256",
                                    "name": "amount",
                                    "type": "uint256"
                                }
                            ],
                            "name": "ProjectCompleted",
                            "type": "event"
                        },
                        {
                            "inputs": [{
                                "internalType": "uint256",
                                "name": "newFee",
                                "type": "uint256"
                            }],
                            "name": "updateServiceFee",
                            "outputs": [],
                            "stateMutability": "nonpayable",
                            "type": "function"
                        },
                        {
                            "inputs": [],
                            "name": "escrowWallet",
                            "outputs": [{
                                "internalType": "address",
                                "name": "",
                                "type": "address"
                            }],
                            "stateMutability": "view",
                            "type": "function"
                        },
                        {
                            "inputs": [],
                            "name": "owner",
                            "outputs": [{
                                "internalType": "address",
                                "name": "",
                                "type": "address"
                            }],
                            "stateMutability": "view",
                            "type": "function"
                        },
                        {
                            "inputs": [{
                                "internalType": "uint256",
                                "name": "",
                                "type": "uint256"
                            }],
                            "name": "projects",
                            "outputs": [{
                                    "internalType": "uint256",
                                    "name": "id",
                                    "type": "uint256"
                                },
                                {
                                    "internalType": "address",
                                    "name": "creator",
                                    "type": "address"
                                },
                                {
                                    "internalType": "address",
                                    "name": "contractor",
                                    "type": "address"
                                },
                                {
                                    "internalType": "uint256",
                                    "name": "amount",
                                    "type": "uint256"
                                },
                                {
                                    "internalType": "bool",
                                    "name": "isCompleted",
                                    "type": "bool"
                                },
                                {
                                    "internalType": "bool",
                                    "name": "isAwarded",
                                    "type": "bool"
                                }
                            ],
                            "stateMutability": "view",
                            "type": "function"
                        },
                        {
                            "inputs": [],
                            "name": "serviceFee",
                            "outputs": [{
                                "internalType": "uint256",
                                "name": "",
                                "type": "uint256"
                            }],
                            "stateMutability": "view",
                            "type": "function"
                        }
                    ];

                    const contract = new web3.eth.Contract(contractABI, contractAddress);
                    const accounts = await web3.eth.getAccounts();
                    const account = accounts[0];

                    console.log('ProjectId:', projectId);
                    console.log('BidPrice:', bidPrice);
                    console.log('ContractorId:', contractorId);
                    console.log('Account:', account);

                    // Validate input values
                    if (!projectId || !contractorId || !bidPrice || !account) {
                        alert('Missing required information to award the project.');
                        return;
                    }

                    const transaction = await contract.methods.awardProject(projectId, contractorId)
                        .send({
                            from: account,
                            value: web3.utils.toWei(bidPrice, 'ether')
                        });

                    console.log('Transaction:', transaction);
                    alert("Project awarded successfully!");

                    // Insert project awarding details into the database
                    $.ajax({
                        type: 'POST',
                        url: 'award_project.php',
                        data: {
                            bid_id: projectId,
                            contractor_id: contractorId
                        },
                        success: function(response) {
                            try {
                                const data = JSON.parse(response);
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert("Error: " + data.message);
                                }
                            } catch (e) {
                                alert("Failed to parse response from the server.");
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert("Error: " + textStatus + " - " + errorThrown);
                        }
                    });
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to award the project. Please try again.');
                }
            } else {
                alert('Please install MetaMask to proceed.');
            }
        });
    });
    </script>
</body>

</html>