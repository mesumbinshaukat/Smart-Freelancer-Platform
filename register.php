<?php
session_start();
include("./connection/connection.php");

if (isset($_POST["submit"])) {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $dob = htmlspecialchars($_POST["dob"]);

    $sql = "INSERT INTO `tbl_user` (`name`, `email`, `password`, `dob`) VALUES (?,?,?,?)";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $dob);
    if ($stmt->execute()) {
        $_SESSION["success"] = "Account created successfully";
        header("location:login.php");
        exit();
    } else {
        $_SESSION["error"] = "Something went wrong";
        header("location:register.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Title -->
    <title>GenAI - AI Content Writing & Copywriting HTML5 Landing Page Template</title>

    <!-- SEO meta tags -->
    <meta name="description" content="Author: Marvel Theme, AI content writing and copywriting html5 and Bootstrap 5 landing page template" />

    <!-- Favicon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml" />

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/plugins.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <div class="wrapper d-flex flex-column justify-between">
        <main class="flex-grow-1">
            <section class="account-section login-page py-6 h-full">
                <div class="container-fluid h-full">
                    <div class="row h-full">
                        <div class="col-lg-6 d-none d-lg-block" data-aos="fade-up-sm" data-aos-delay="50">
                            <div class="bg-dark-blue-4 border rounded-4 h-full p-6 p-md-20 text-center d-flex flex-column justify-center">
                                <h2 class="text-white mb-12">
                                    Unlock the Power of <br class="d-none d-xl-block" />
                                    <span class="text-primary-dark">GenAI</span> Copywriting Tool
                                </h2>
                                <img src="assets/images/screens/screen-5.png" alt="" class="img-fluid w-full" />
                            </div>
                        </div>
                        <div class="col-lg-6" data-aos="fade-up-sm" data-aos-delay="100">
                            <div class="close-btn">
                                <a href="index.html" class="icon bg-gradient-3 text-white w-12 h-12 rounded p-3 border border-white border-opacity-10 d-flex align-center justify-center ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M18 6 6 18M6 6l12 12" />
                                        </g>
                                    </svg>
                                </a>
                            </div>
                            <div class="account-wrapper h-full d-flex flex-column justify-center">
                                <div class="text-center">
                                    <a href="">
                                        <img src="assets/images/logo.svg" alt="" class="img-fluid" width="165" />
                                    </a>
                                    <div class="vstack gap-4 mt-10">
                                        <button type="button" class="btn account-btn py-4">
                                            <img src="assets/images/icons/google.svg" alt="" width="24" class="img-fluid icon" />
                                            <span>Continue With Google</span>
                                        </button>
                                        <button type="button" class="btn account-btn py-4">
                                            <img src="assets/images/icons/apple.svg" alt="" width="24" class="img-fluid icon" />
                                            <span>Continue With Apple</span>
                                        </button>
                                    </div>

                                    <div class="divider-with-text my-10">
                                        <span>Or register with email</span>
                                    </div>

                                    <form method="post" action="#" class="vstack gap-4" onsubmit="return validatePassword()">
                                        <div class="text-start">
                                            <div class="input-group with-icon">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                        <g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2">
                                                            <path d="M2.25 5.25a1.5 1.5 0 0 1 1.5-1.5h10.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5v-7.5Z" />
                                                            <path d="M2.25 5.25 9 9.75l6.75-4.5" />
                                                        </g>
                                                    </svg>
                                                </span>
                                                <input type="email" class="form-control rounded-2 py-4" placeholder="Enter Your Email" name="email" required />
                                            </div>
                                        </div>
                                        <div class="text-start">
                                            <div class="input-group with-icon">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke="none" d="M0 0h24v24H0z" />
                                                        <circle cx="12" cy="7" r="4" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2" />
                                                    </svg>
                                                </span>
                                                <input type="text" class="form-control rounded-2 py-4" placeholder="Enter Your Name" name="name" required />
                                            </div>
                                        </div>

                                        <div class="text-start">
                                            <div class="input-group with-icon">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke="none" d="M0 0h24v24H0z" />
                                                        <rect x="4" y="5" width="16" height="16" rx="2" />
                                                        <line x1="16" y1="3" x2="16" y2="7" />
                                                        <line x1="8" y1="3" x2="8" y2="7" />
                                                        <line x1="4" y1="11" x2="20" y2="11" />
                                                        <line x1="11" y1="15" x2="12" y2="15" />
                                                        <line x1="12" y1="15" x2="12" y2="18" />
                                                    </svg>
                                                </span>
                                                <input type="date" class="form-control rounded-2 py-4" placeholder="Enter Your Date of Birth" name="dob" required />
                                            </div>
                                        </div>

                                        <div class="text-start">
                                            <div class="input-group with-icon">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke="none" d="M0 0h24v24H0z" />
                                                        <path d="M12 3a12 12 0 0 0 8.5 3A12 12 0 0 1 12 21 12 12 0 0 1 3.5 6 12 12 0 0 0 12 3" />
                                                        <circle cx="12" cy="11" r="1" />
                                                        <path d="M12 12v2.5" />
                                                    </svg>
                                                </span>
                                                <input type="password" id="password" class="form-control rounded-2 py-4" placeholder="Password" name="password" required />
                                            </div>
                                        </div>
                                        <div class="text-start">
                                            <div class="input-group with-icon">
                                                <span class="icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24">
                                                        <path stroke="none" d="M0 0h24v24H0z" />
                                                        <path d="M12 3a12 12 0 0 0 8.5 3A12 12 0 0 1 12 21 12 12 0 0 1 3.5 6 12 12 0 0 0 12 3" />
                                                        <circle cx="12" cy="11" r="1" />
                                                        <path d="M12 12v2.5" />
                                                    </svg>
                                                </span>
                                                <input type="password" id="confirm_password" class="form-control rounded-2 py-4" placeholder="Confirm Password" required />
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary-dark w-full py-4" name="submit">
                                                Create an account
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <p>
                                                Already have an account?
                                                <a href="login.html" class="text-decoration-none"> Log in </a>
                                            </p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- JS -->
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function validatePassword() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>

    <?php
    if (isset($_SESSION["error"])) {
        echo "<script>toastr.error('" . $_SESSION["success"] . "');</script>";
        session_unset();
    }
    ?>
</body>

</html>