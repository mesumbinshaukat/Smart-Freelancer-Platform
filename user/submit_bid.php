<?php
session_start();
include("../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $bid_letter = $_POST['bid_letter'];
    // $bid_date = date('Y-m-d H:i:s');
    $bid_price = $_POST['bid_price'];
    $project_id = $_POST['project_id'];

    $stmt = $con->prepare("INSERT INTO `tbl_bids` (`user_id`, `bid_letter`, `bid_price`, `project_id`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issi", $user_id, $bid_letter, $bid_price, $project_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to place bid.']);
    }
    $stmt->close();
}
