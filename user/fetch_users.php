<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    echo json_encode([]);
    exit();
}

$user_query = "SELECT id, name FROM tbl_user WHERE id != ?";
$stmt = $con->prepare($user_query);
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);