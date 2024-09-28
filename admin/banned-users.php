<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["login_type"]) || !isset($_COOKIE["login_checker"])) {
    $_SESSION["error"] = "Please login first";
    header("location:login.php");
    exit();
}

// Fetch all banned users
$banned_users_query = "
    SELECT 
        u.id, u.name, u.email, b.time_period, b.banned_at 
    FROM 
        tbl_banned_user b
    JOIN 
        tbl_user u 
    ON 
        b.bann_user_id = u.id";

$banned_users_result = mysqli_query($con, $banned_users_query);
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
                    <div class="breadcrumb-title pe-3">Banned Users</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Banned Users</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->

                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <h6 class="mb-0 text-uppercase">Banned Users List</h6>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Banned At</th>
                                        <th>Time Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($banned_users_result) > 0) {
                                        while ($banned_user = mysqli_fetch_assoc($banned_users_result)) {
                                            echo "<tr>";
                                            echo "<td>" . $banned_user['id'] . "</td>";
                                            echo "<td>" . $banned_user['name'] . "</td>";
                                            echo "<td>" . $banned_user['email'] . "</td>";
                                            echo "<td>" . $banned_user['banned_at'] . "</td>";
                                            echo "<td>" . $banned_user['time_period'] . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No banned users found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <a href="ban.php" class="btn btn-secondary">Back to Ban Users</a>

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