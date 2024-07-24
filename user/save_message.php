<?php
session_start();
include("../connection/connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $freelancer_id = $_POST['freelancer_id'];
    $client_id = $_POST['client_id'];
    $attachments = '';

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $file = $_FILES['attachment'];
        $filename = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $upload_path = '';
        if (in_array($file_ext, ['jpeg', 'jpg', 'png', 'gif'])) {
            $upload_path = "./assets/messages/images/";
        } elseif ($file_ext == 'zip') {
            $upload_path = "./assets/messages/zip/";
        }

        if ($upload_path) {
            move_uploaded_file($file_tmp, $upload_path . $filename);
            $attachments = $upload_path . $filename;
        }
    }

    $stmt = $con->prepare("INSERT INTO `tbl_messages`(`message`, `freelancer_id`, `client_id`, `attachments`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siis", $message, $freelancer_id, $client_id, $attachments);
    $stmt->execute();
    $stmt->close();
    $con->close();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}