<?php
session_start();
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();

use Appwrite\Client;
use Appwrite\Services\Account;

$client = new Client();
$client
    ->setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
    ->setProject($_ENV["project_id"]); // Your Project ID

$account = new Account($client);

// Check if the user is logged in locally
if (isset($_COOKIE["email"]) && isset($_COOKIE["user_logged_in_bool"])) {
    try {
        // Attempt to get the authenticated user
        $user = $account->get();

        // If the user is authenticated in Appwrite, delete all active sessions
        if ($user) {
            $account->deleteSessions(); // Delete all active sessions
        }
    } catch (Exception $e) {
        // If the user is not authenticated in Appwrite, ignore the error and proceed with logout
        if (strpos($e->getMessage(), 'missing scope') === false) {
            // Log or handle the exception differently if needed
            echo "Error: " . $e->getMessage();
            exit();
        }
    }

    // Clear cookies and session
    setcookie("email", "", time() - 3600, "/");
    setcookie("user_logged_in_bool", "", time() - 3600, "/");
    unset($_COOKIE["email"]);
    unset($_COOKIE["user_logged_in_bool"]);
    $_SESSION["success"] = "Logged out successfully";

    // Redirect to login page
    header("location:../login.php");
    exit();
} else {
    // If no user is logged in locally, just redirect to login
    header("location:../login.php");
    exit();
}