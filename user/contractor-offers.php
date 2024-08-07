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
                            <table id="contractor-offers" class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th data-dt-order="enable" data-dt-order="icon-only">#</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Contractor Name</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Proposal</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Offer Date</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Offer Price</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Project Name</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Project Created By</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Award
                                            Project</th>
                                        <th data-dt-order="enable" data-dt-order="icon-only">Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $select_tbl_wallet_address = "SELECT * FROM `tbl_wallet_address` WHERE `user_id` = '{$row['bidder_id']}'";
                                            $result_tbl_wallet_address = mysqli_query($con, $select_tbl_wallet_address);
                                            $user_details_cypto = mysqli_fetch_assoc($result_tbl_wallet_address);
                                            $contractor_wallet_address = !empty($user_details_cypto['wallet_address']) ? $user_details_cypto['wallet_address'] : '';
                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . " ETH</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($user_details['name']) . "</td>";
                                            echo "<td><button class='btn btn-primary award-project-btn' data-project-id='" . $row['project_id'] . "' data-bid-price='" . $row['bid_price'] . "' data-contractor-id='" . $row['bidder_id'] . "' data-contractor-wallet-address='" . $contractor_wallet_address . "'>Award</button></td>";
                                            echo "<td><button class='btn btn-light chat-button' data-bs-toggle='modal' data-bs-target='#chatModal_" . $row['bid_id'] . "' data-bid-id='" . $row['bid_id'] . "' data-contractor-id='" . $row['bidder_id'] . "'><i class='bx bx-message-dots'></i></button></td>";
                                            echo "</tr>";

                                            // Include the chat modal
                                            try {
                                                chatModal($row['bid_id'], $row['bidder_id'], $user_details['id']);
                                            } catch (Exception $e) {
                                                echo $e->getMessage();
                                            }
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
                    <input type="hidden" id="modal-contractor-wallet-address">
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
    $(document).ready(function() {

        $('.award-project-btn').click(function() {
            $('#modal-project-id').val($(this).data('project-id'));
            $('#modal-bid-price').val($(this).data('bid-price'));
            $('#modal-contractor-id').val($(this).data('contractor-id'));
            $('#modal-contractor-wallet-address').val($(this).data('contractor-wallet-address'));
            $('#awardProjectModal').modal('show');
        });

        $('#confirm-award-btn').click(async function() {
            const projectId = $('#modal-project-id').val();
            const bidPrice = $('#modal-bid-price').val();
            const contractorId = $('#modal-contractor-id').val();
            const contractorAddress = $('#modal-contractor-wallet-address').val();

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
                    if (!projectId || !contractorId || !bidPrice || !account || !
                        contractorAddress) {
                        alert('Missing required information to award the project.');
                        return;
                    }

                    const transaction = await contract.methods.awardProject(projectId,
                            contractorAddress)
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