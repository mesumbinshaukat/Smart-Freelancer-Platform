<?php
session_start();
include("./connection/connection.php");

require_once realpath(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Account;

// Initialize Appwrite client
$client = new Client();
$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
    ->setProject($_ENV["project_id"]); // Your Project ID

$account = new Account($client);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Retrieve the user's details after OAuth login
        $user = $account->get(); // This retrieves the authenticated user's details

        $email = $user['email'];
        $name = $user['name'];

        // Check if the user already exists in the database
        $sql = "SELECT * FROM `tbl_user` WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // User found, treat this as a login
            setcookie("email", $email, time() + (86400 * 30), "/");
            setcookie("user_logged_in_bool", true, time() + (86400 * 30), "/");

            // Redirect to dashboard
            header("Location: user/index.php");
        } else {
            // Insert the new user if not already registered
            $sqlInsert = "INSERT INTO `tbl_user` (`name`, `email`, `password`, `dob`) VALUES (?, ?, NULL, NULL)";
            $stmtInsert = $con->prepare($sqlInsert);
            $stmtInsert->bind_param("ss", $name, $email);
            $stmtInsert->execute();

            // Set cookies for logged-in user
            setcookie("email", $email, time() + (86400 * 30), "/");
            setcookie("user_logged_in_bool", true, time() + (86400 * 30), "/");

            // Redirect to dashboard
            header("Location: user/index.php");
        }
        exit();
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to retrieve user information: ' . $e->getMessage()]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}