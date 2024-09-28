<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["login_type"]) || !isset($_COOKIE["login_checker"])) {
    $_SESSION["error"] = "Please login first";
    header("location:login.php");
    exit();
}

// Fetch all users
$users_query = "SELECT * FROM `tbl_user`";
$users_result = mysqli_query($con, $users_query);

// Initialize counts for bids, assigned, and completed projects
$total_bids_count = 0;
$total_assigned_projects_count = 0;
$total_completed_projects_count = 0;

// Loop through users and accumulate their project counts
while ($user = mysqli_fetch_assoc($users_result)) {
    $user_id = $user['id'];

    // Fetch bids count for this user
    $bids_query = "SELECT * FROM tbl_bids WHERE user_id = $user_id";
    $bids_result = mysqli_query($con, $bids_query);
    $bids_count = mysqli_num_rows($bids_result);
    $total_bids_count += $bids_count;

    // Fetch assigned projects count for this user
    $assigned_projects_query = "SELECT * FROM tbl_project_assigned WHERE user_id = $user_id";
    $assigned_projects_result = mysqli_query($con, $assigned_projects_query);
    $assigned_projects_count = mysqli_num_rows($assigned_projects_result);
    $total_assigned_projects_count += $assigned_projects_count;

    // Fetch completed projects count for this user
    $completed_projects_query = "SELECT * FROM tbl_completion WHERE user_id = $user_id";
    $completed_projects_result = mysqli_query($con, $completed_projects_query);
    $completed_projects_count = mysqli_num_rows($completed_projects_result);
    $total_completed_projects_count += $completed_projects_count;
}
?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include("./partials/head.php") ?>
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

                <!-- Summary Cards for Bids, Assigned Projects, and Completed Projects -->
                <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-deepblue">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><?php echo $total_bids_count; ?></h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-dollar fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Total Bids</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ohhappiness">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><?php echo $total_assigned_projects_count; ?></h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-folder fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Assigned Projects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 bg-gradient-ibiza">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><?php echo $total_completed_projects_count; ?></h5>
                                    <div class="ms-auto">
                                        <i class='bx bx-check fs-3 text-white'></i>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center text-white">
                                    <p class="mb-0">Completed Projects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->

                <!-- Projects Summary Section for All Users -->
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0">Assigned Projects Summary for All Users</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Project Title</th>
                                        <th>Deadline</th>
                                        <th>Fee</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Reset user result and fetch assigned projects for all users
                                    mysqli_data_seek($users_result, 0); // Reset pointer to the first row
                                    while ($user = mysqli_fetch_assoc($users_result)) {
                                        $user_id = $user['id'];
                                        $username = $user['name'];

                                        // Fetch assigned projects for this user
                                        $assigned_projects_query = "SELECT * FROM tbl_project_assigned WHERE user_id = $user_id";
                                        $assigned_projects_result = mysqli_query($con, $assigned_projects_query);

                                        while ($assigned_project = mysqli_fetch_assoc($assigned_projects_result)) {
                                            $project_id = $assigned_project['project_id'];
                                            $project_query = "SELECT * FROM tbl_projects WHERE id = $project_id";
                                            $project_result = mysqli_query($con, $project_query);
                                            $project = mysqli_fetch_assoc($project_result);
                                    ?>
                                            <tr>
                                                <td><?php echo $username; ?></td>
                                                <td><?php echo $project['project_title']; ?></td>
                                                <td><?php echo $project['project_deadline']; ?></td>
                                                <td>$<?php echo $project['project_fee']; ?></td>
                                                <td><?php echo $project['status']; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--End Row-->

            </div>
        </div>
        <!--end page wrapper -->

        <?php include "./partials/last_code.php"; ?>

        <?php
        if (isset($_SESSION["success"])) {
            echo "<script>toastr.success('" . $_SESSION["success"] . "');</script>";
        }
        session_unset();
        ?>
    </div>
    <!--end wrapper-->

    <!--start switcher-->
    <?php include "./partials/switcher.php" ?>
    <!--end switcher-->

    <!-- Bootstrap JS -->
    <?php include "./partials/scripts.php"; ?>
</body>

</html>