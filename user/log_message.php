<?php
session_start();
include("../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $freelancer_id = $_POST['freelancer_id'];
    $client_id = $_POST['client_id'];
    $attachments = isset($_POST['attachments']) ? json_encode($_POST['attachments']) : null;

    $stmt = $con->prepare("INSERT INTO tbl_messages (message, freelancer_id, client_id, attachments, timestamp) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("siis", $message, $freelancer_id, $client_id, $attachments);

    if ($stmt->execute()) {
        echo "Message logged successfully";
    } else {
        echo "Error logging message: " . $con->error;
    }
}