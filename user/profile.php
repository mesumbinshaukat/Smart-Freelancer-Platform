<?php
session_start();
include("../connection/connection.php");

require __DIR__ . '/partials/fetch_user_details.php';

$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update_fields = [];
    $update_values = [];

    if (!empty($_POST["username"])) {
        $update_fields[] = "name = ?";
        $update_values[] = $_POST["username"];
    }
    if (!empty($_POST["email"])) {
        $update_fields[] = "email = ?";
        $update_values[] = $_POST["email"];
    }
    if (!empty($_POST["dob"])) {
        $update_fields[] = "dob = ?";
        $update_values[] = $_POST["dob"];
    }
    if (!empty($_POST["address"])) {
        $update_fields[] = "address = ?";
        $update_values[] = $_POST["address"];
    }
    if (!empty($_POST["phone"])) {
        $update_fields[] = "phone_number = ?";
        $update_values[] = $_POST["phone"];
    }

    if (count($update_fields) > 0) {
        $update_values[] = $user_details["id"];
        $sql = "UPDATE `tbl_user` SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($update_values)), ...$update_values);
        if ($stmt->execute()) {
            $_SESSION["success"] = "Profile updated successfully.";
        } else {
            $_SESSION["error"] = "Failed to update profile.";
        }
    } else {
        $_SESSION["error"] = "No changes made.";
    }

    header("Location: profile.php");
    exit();
}
?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
    <script>
    function enableEdit(field) {
        document.querySelector(`input[name="${field}"]`).disabled = false;
        document.getElementById("update-button").hidden = false;
    }

    function disableEdit(field) {
        document.querySelector(`input[name="${field}"]`).disabled = true;
    }
    </script>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include("./partials/sidebar.php"); ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <?php include("./partials/navbar.php"); ?>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!-- CODE GOES HERE -->
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h6 class="mb-0 text-uppercase">Profile</h6>
                        <hr />
                        <form method="post" class="row g-3">
                            <div class="card">
                                <div class="card-body">
                                    <label class="form-label form-control">Username:</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg mb-3" type="text"
                                            placeholder="Username" name="username" value="<?= $user_details["name"] ?>"
                                            disabled>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="enableEdit('username')"><i class="bx bx-edit"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Email:</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg mb-3" type="email"
                                            placeholder="Email" name="email" value="<?= $user_details["email"] ?>"
                                            disabled>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="enableEdit('email')"><i class="bx bx-edit"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Date-Of-Birth (DOB):</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg mb-3" type="date" name="dob"
                                            placeholder="Date-Of-Birth" value="<?= $user_details["dob"] ?>" disabled>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="enableEdit('dob')"><i class="bx bx-edit"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Address:</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg mb-3" type="text"
                                            placeholder="Address" name="address"
                                            value="<?= empty($user_details["address"]) ? "N/A" : $user_details["address"] ?>"
                                            disabled>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="enableEdit('address')"><i class="bx bx-edit"></i></button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Phone:</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-lg mb-3" type="text" placeholder="Phone"
                                            name="phone"
                                            value="<?= empty($user_details["phone_number"]) ? "N/A" : $user_details["phone_number"] ?>"
                                            disabled>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="enableEdit('phone')"><i class="bx bx-edit"></i></button>
                                    </div>
                                </div>

                                <div class="card-body text-center">
                                    <button type="submit" class="btn btn-primary" id="update-button"
                                        hidden>Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!--end switcher-->
        <?php include "./partials/last_code.php"; ?>
        <?php
        if (isset($_SESSION["success"])) {
            echo "<script>toastr.success('" . $_SESSION["success"] . "');</script>";
            unset($_SESSION["success"]);
        }

        if (isset($_SESSION["error"])) {
            echo "<script>toastr.error('" . $_SESSION["error"] . "');</script>";
            unset($_SESSION["error"]);
        }
        ?>
</body>

</html>