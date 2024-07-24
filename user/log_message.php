// log_message.php

<?php
session_start();
include("../connection/connection.php");

function logMessage($message, $freelancerId, $clientId, $attachments)
{
    global $con;
    $stmt = $con->prepare("INSERT INTO tbl_messages (message, freelancer_id, client_id, attachments) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $message, $freelancerId, $clientId, $attachments);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $freelancer_id = $_POST['freelancer_id'];
    $client_id = $_POST['client_id'];
    $attachments = $_POST['attachments'];

    logMessage($message, $freelancer_id, $client_id, $attachments);
}
?>