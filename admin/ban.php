<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["login_type"]) || !isset($_COOKIE["login_checker"])) {
    $_SESSION["error"] = "Please login first";
    header("location:login.php");
    exit();
}

// Fetch all users who are not banned
$users_query = "SELECT u.* FROM tbl_user u LEFT JOIN tbl_banned_user b ON u.id = b.bann_user_id WHERE b.bann_user_id IS NULL";
$users_result = mysqli_query($con, $users_query);

// Handle ban action
if (isset($_GET['ban_id'])) {
    $user_id = intval($_GET['ban_id']);
    header("location:ban-user.php?user_id=" . $user_id);
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
                    <div class="breadcrumb-title pe-3">Users</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Ban Users</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <h6 class="mb-0 text-uppercase">Users List</h6>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($user = mysqli_fetch_assoc($users_result)) {
                                        echo "<tr>";
                                        echo "<td>" . $user['id'] . "</td>";
                                        echo "<td>" . $user['name'] . "</td>";
                                        echo "<td>" . $user['email'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='ban.php?ban_id=" . $user['id'] . "' class='btn btn-danger btn-sm'>Ban</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>

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