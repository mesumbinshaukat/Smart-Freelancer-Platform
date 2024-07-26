<?php
session_start();
include("../connection/connection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];

    $stmt = $con->prepare("SELECT `message`, `attachments`, `date_time` FROM `tbl_messages` WHERE (`freelancer_id` = ? AND `client_id` = ?) OR (`freelancer_id` = ? AND `client_id` = ?) ORDER BY timestamp ASC");
    $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();

        echo json_encode($messages);
    } else {
        echo json_encode(['error' => 'Failed to fetch messages']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
