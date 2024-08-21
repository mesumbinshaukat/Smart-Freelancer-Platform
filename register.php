<?php
session_start();
include("./connection/connection.php");

require_once realpath(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__));
$dotenv->load();


if (isset($_POST["submit"])) {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $dob = htmlspecialchars($_POST["dob"]);

    // Manual registration
    $sql = "INSERT INTO `tbl_user` (`name`, `email`, `password`, `dob`) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $dob);
    if ($stmt->execute()) {
        $_SESSION["success"] = "Account created successfully";
        header("location:login.php");
        exit();
    } else {
        $_SESSION["error"] = "Something went wrong during registration.";
        header("location:register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GenAI - Registration</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Appwrite JS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/appwrite@15.0.0/dist/iife/sdk.min.js"></script>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
    .wrapper {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .account-wrapper {
        max-width: 400px;
        width: 100%;
        padding: 20px;
        background: grey;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn.account-btn {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #4285F4;
        color: white;
        border: none;
    }

    .btn.account-btn:hover {
        background-color: #357ae8;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="account-wrapper text-center">
            <a href="">
                <img src="assets/images/logo.svg" alt="Logo" width="165" />
            </a>
            <div class="vstack gap-4 mt-10">
                <!-- Google OAuth Button -->
                <button type="button" id="googleBtn" class="btn account-btn py-4">
                    <img src="assets/images/icons/google.svg" alt="" width="24" class="img-fluid icon" />
                    <span>Continue With Google</span>
                </button>
            </div>

            <div class="divider-with-text my-10">
                <span>Or register with email</span>
            </div>

            <form method="post" action="#" class="vstack gap-4" onsubmit="return validatePassword()">
                <div class="text-start">
                    <input type="email" class="form-control" placeholder="Enter Your Email" name="email" required />
                </div>
                <div class="text-start">
                    <input type="text" class="form-control" placeholder="Enter Your Name" name="name" required />
                </div>
                <div class="text-start">
                    <input type="date" class="form-control" placeholder="Enter Your Date of Birth" name="dob"
                        required />
                </div>
                <div class="text-start">
                    <input type="password" id="password" class="form-control" placeholder="Password" name="password"
                        required />
                </div>
                <div class="text-start">
                    <input type="password" id="confirm_password" class="form-control" placeholder="Confirm Password"
                        required />
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary-dark w-full py-4" name="submit">Create an
                        account</button>
                </div>
                <div class="text-center">
                    <p>Already have an account? <a href="login.php">Log in</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
    function validatePassword() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;
        if (password !== confirmPassword) {
            toastr.error("Passwords do not match.");
            return false;
        }
        return true;
    }

    // Initialize Appwrite Client
    const client = new Appwrite.Client();
    client
        .setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
        .setProject('<?php echo $_ENV["project_id"]; ?>'); // Your Project ID

    const account = new Appwrite.Account(client);

    // Google OAuth login flow
    document.getElementById('googleBtn').addEventListener('click', function() {
        account.createOAuth2Session('google',
            'http://localhost:8080/Smart-Freelancer-Platform/oauth_callback.php',
            'http://localhost:8080/Smart-Freelancer-Platform/register.php?error=oauth_failure');
    });

    async function fetchUserDetails() {
        try {
            const user = await account.get();
            // Send the user data to the server for storage
            await sendUserDataToServer(user);
        } catch (error) {
            // toastr.error('Failed to retrieve user information.');
            console.log("Failed to retrieve user information:", error);
        }
    }

    // Function to send user data to the server via AJAX
    function sendUserDataToServer(user) {
        return $.ajax({
            url: 'oauth_callback.php',
            method: 'POST',
            data: {
                email: user.email,
                name: user.name,
                userId: user.$id
            },
            success: function(response) {
                window.location.href = 'login.php'; // Redirect to login page after success
            },
            error: function(error) {
                toastr.error('Failed to store user information.');
            }
        });
    }

    // Call this function after the OAuth flow is complete
    fetchUserDetails();
    </script>

</body>

</html>