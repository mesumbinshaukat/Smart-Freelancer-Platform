<?php
session_start();
if (isset($_COOKIE["email"]) && isset($_COOKIE["user_logged_in_bool"])) {
    setcookie("email", "", time() - 3600, "/");
    setcookie("user_logged_in_bool", "", time() - 3600, "/");
    unset($_COOKIE["email"]);
    unset($_COOKIE["user_logged_in_bool"]);
    $_SESSION["success"] = "Logged out successfully";
    header("location:../login.php");
    exit();
}
