<?php
session_start();
include("../connection/connection.php");

if (!isset($_POST['bid_id']) || !isset($_POST['contractor_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$bid_id = $_POST['bid_id'];
$contractor_id = $_POST['contractor_id'];

// Fetch project ID from bid ID
$query = "SELECT project_id FROM tbl_bids WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $bid_id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

if (!$project) {
    echo json_encode(['success' => false, 'message' => 'Project not found']);
    exit();
}

$project_id = $project['project_id'];

// Update project status to awarded
$query = "UPDATE tbl_projects SET status = 'awarded' WHERE id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();

// Insert into tbl_project_assigned
$query = "INSERT INTO tbl_project_assigned (project_id,  user_id) VALUES (?, ?)";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $project_id, $contractor_id);
$stmt->execute();

echo json_encode(['success' => true, 'message' => 'Project awarded successfully']);
