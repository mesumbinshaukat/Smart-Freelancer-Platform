<?php
session_start();
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__ . '/..'));
$dotenv->load();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>

    <!-- Appwrite SDK -->
    <script src="https://cdn.jsdelivr.net/npm/appwrite@15.0.0/dist/iife/sdk.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Appwrite Client
        const client = new Appwrite.Client();
        client
            .setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
            .setProject('<?php echo $_ENV["project_id"]; ?>'); // Your Project ID

        const account = new Appwrite.Account(client);

        // Function to delete the current session for Google-authenticated users
        async function logoutGoogleUser() {
            try {
                await account.deleteSession('current');
            } catch (error) {
                console.error('Failed to log out from Google:', error);
            }
        }

        // Function to clear cookies
        function clearCookies() {
            document.cookie = "email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "user_logged_in_bool=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }

        // Main logout function
        function logoutUser() {
            const loggedInWithGoogle = document.cookie.includes(
            "google_oauth=true"); // Check if the user is logged in via Google

            if (loggedInWithGoogle) {
                logoutGoogleUser().then(() => {
                    clearCookies();
                    redirectToLogin();
                });
            } else {
                clearCookies();
                redirectToLogin();
            }
        }

        // Redirect to login page
        function redirectToLogin() {
            // Optionally, clear the session in PHP by requesting a PHP session unset via AJAX
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "clear_session.php", true); // An endpoint to clear PHP session
            xhr.send();

            // Redirect to login page after logout
            window.location.href = '../login.php';
        }

        // Call the logout function when the page loads
        logoutUser();
    });
    </script>
</head>

<body>
    <h1>Logging out...</h1>
</body>

</html>