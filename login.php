<?php
session_start();
include("./connection/connection.php");

require_once realpath(__DIR__ . '/vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(realpath(__DIR__));
$dotenv->load();

// Redirect user to dashboard if already logged in
if (isset($_COOKIE["email"]) && !empty($_COOKIE["email"])) {
    header("location: http://localhost:8080/Smart-Freelancer-Platform/user/index.php");
    exit();
}

if (isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    $sql = "SELECT * FROM `tbl_user` WHERE email = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["success"] = "Login successful";
            setcookie("email", $email, time() + (86400 * 30), "/");
            setcookie("user_logged_in_bool", true, time() + (86400 * 30), "/");
            header("location:./user/index.php");
        } else {
            $_SESSION["error"] = "Password is incorrect";
            header("location:login.php");
        }
    } else {
        $_SESSION["error"] = "Email is not registered";
        header("location:login.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Contractor - Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Appwrite JS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/appwrite@15.0.0/dist/iife/sdk.min.js"></script>

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
                <span>Or sign in with email</span>
            </div>

            <!-- Email/Password Login Form -->
            <form method="post" class="vstack gap-4">
                <div class="text-start">
                    <input type="email" class="form-control rounded-2 py-4" placeholder="Enter Your Email" name="email"
                        required />
                </div>
                <div class="text-start">
                    <input type="password" class="form-control rounded-2 py-4" placeholder="Password" name="password"
                        required />
                </div>
                <div class="form-text mt-2">
                    <a href="forgot-password.html" class="text-decoration-none">Forgot Password?</a>
                </div>
                <div class="text-center">
                    <button type="submit" name="submit" class="btn btn-primary-dark w-full py-4">Sign In</button>
                </div>
                <div class="text-center">
                    <p>Don't have an account? <a href="http://localhost:8080/Smart-Freelancer-Platform/register.php"
                            class="text-decoration-none">Sign Up for Free</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Appwrite OAuth2 Handling -->
    <script>
    const client = new Appwrite.Client();
    client
        .setEndpoint('https://cloud.appwrite.io/v1') // Your Appwrite Endpoint
        .setProject('<?php echo $_ENV["project_id"]; ?>'); // Your Project ID

    const account = new Appwrite.Account(client);

    document.getElementById('googleBtn').addEventListener('click', function() {
        account.createOAuth2Session('google', window.location.href,
            'http://localhost:8080/Smart-Freelancer-Platform/login.php?error=oauth_failure');
    });

    async function checkAndLoginUser() {
        try {
            const user = await account.get(); // Get the authenticated user's details

            if (user) {
                // User is authenticated, set cookies and redirect
                document.cookie = "email=" + user.email + "; path=/; max-age=" + (86400 * 30);
                document.cookie = "user_logged_in_bool=true; path=/; max-age=" + (86400 * 30);

                // Redirect to dashboard
                window.location.href = 'http://localhost:8080/Smart-Freelancer-Platform/user/index.php';
            }
        } catch (error) {
            console.error('Failed to authenticate user:', error);
            // toastr.error('Failed to authenticate with Google.');
        }
    }

    // Call this function after the page reloads post-OAuth
    if (window.location.search.includes('oauth_failure')) {
        toastr.error('Google authentication failed.');
    } else {
        checkAndLoginUser();
    }
    </script>

    <!-- Show Toastr Notifications -->
    <?php
    if (isset($_SESSION["success"])) {
        echo "<script>toastr.success('" . $_SESSION["success"] . "');</script>";
        session_unset();
    }
    if (isset($_SESSION["error"])) {
        echo "<script>toastr.error('" . $_SESSION["error"] . "');</script>";
        session_unset();
    }
    ?>
</body>

</html>