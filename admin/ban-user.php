<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["login_type"]) || !isset($_COOKIE["login_checker"])) {
    $_SESSION["error"] = "Please login first";
    header("location:login.php");
    exit();
}

// Check if user_id is passed
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
} else {
    $_SESSION["error"] = "No user selected for banning";
    header("location:ban.php");
    exit();
}

// Handle form submission for banning the user
if (isset($_POST["ban"])) {
    $admin_id = 1;  // Assuming you set admin_id based on the logged-in admin
    $time_period = htmlspecialchars($_POST["time_period"]);
    $banned_at = date("Y-m-d H:i:s");

    // Insert banned user details into tbl_banned_user
    $ban_query = "INSERT INTO tbl_banned_user (bann_user_id, admin_id, time_period, banned_at) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($ban_query);
    $stmt->bind_param("iiss", $user_id, $admin_id, $time_period, $banned_at);

    if ($stmt->execute()) {
        $_SESSION["success"] = "User banned successfully";
    } else {
        $_SESSION["error"] = "Failed to ban user";
    }

    header("location:ban.php");
    exit();
}

?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <?php include "./partials/sidebar.php"; ?>
        <!--end sidebar wrapper -->
        <!--start header -->
        <header>
            <?php include "./partials/navbar.php"; ?>
        </header>
        <!--end header -->
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!--breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Ban User</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Ban User</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h6 class="mb-0 text-uppercase">Ban User</h6>
                        <hr />
                        <form method="post" class="row g-3">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                                    <div class="mb-3">
                                        <label for="time_period" class="form-label">Ban Until</label>
                                        <input class="form-control form-control-lg mb-3" type="date" name="time_period"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="banned_at" class="form-label">Banned At</label>
                                        <input class="form-control form-control-lg mb-3" type="text" name="banned_at"
                                            value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger" name="ban">Ban User</button>
                            <a href="ban.php" class="btn btn-secondary">Back to User List</a>
                        </form>

                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
        <!--end page wrapper -->
        <?php include "./partials/last_code.php"; ?>
    </div>

    <!--start switcher-->
    <?php include "./partials/switcher.php" ?>
    <!--end switcher-->
    <!-- Bootstrap JS -->
    <?php include "./partials/scripts.php" ?>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<script>toastr.error("' . $_SESSION["error"] . '")</script>';
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"])) {
        echo '<script>toastr.success("' . $_SESSION["success"] . '")</script>';
        unset($_SESSION["success"]);
    }

    ?>
</body>

</html>