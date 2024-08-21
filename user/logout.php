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
        // Attempt to delete the current session in Appwrite
        $account->deleteSession("current"); // Delete the current session in Appwrite
    } catch (Exception $e) {
        // If there's an error related to missing scope or no session, ignore it
        if (strpos($e->getMessage(), 'missing scope') === false) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }

    // Clear local cookies and session data
    setcookie("email", "", time() - 3600, "/");
    setcookie("user_logged_in_bool", "", time() - 3600, "/");
    unset($_COOKIE["email"]);
    unset($_COOKIE["user_logged_in_bool"]);
    // $_SESSION["success"] = "Logged out successfully";

    // Redirect to login page
    header("location:../login.php");
    exit();
} else {
    // If no user is logged in locally, just redirect to login
    header("location:../login.php");
    exit();
}