<?php
session_start();
include("../connection/connection.php");

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int) $_POST['user_id'];
    $bid_letter = $_POST['bid_letter'];
    $bid_price = (string) $_POST['bid_price'];
    $project_id = (int) $_POST['project_id'];

    $stmt = $con->prepare("INSERT INTO `tbl_bids` (`user_id`, `bid_letter`, `bid_price`, `project_id`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issi", $user_id, $bid_letter, $bid_price, $project_id);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = "Failed to place bid.";
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['error'] = "Invalid request method.";
}

header('Content-Type: application/json');
echo json_encode($response);
exit(); // Ensure no further output