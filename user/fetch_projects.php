<?php
session_start();
include("../connection/connection.php");

require __DIR__ . '/partials/fetch_user_details.php';

$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    echo json_encode(["error" => "Please login first"]);
    exit();
}

$user_id = (int) $user_details["id"];

// Fetch projects from the database
$query = "SELECT p.*, u.name FROM tbl_projects p INNER JOIN tbl_user u ON p.u_id = u.id WHERE p.u_id != ? ORDER BY p.created_at DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
while ($row = $result->fetch_assoc()) {
    $projects[] = $row;
}

echo json_encode($projects);
