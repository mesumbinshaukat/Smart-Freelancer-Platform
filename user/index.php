<?php
session_start();
include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

$user_details = get_user_info($_COOKIE["email"], $con);
$user_id = $user_details['id'];

// Fetch user-related data
// Fetch bids
$bids_query = "SELECT * FROM tbl_bids WHERE user_id = $user_id";
$bids_result = mysqli_query($con, $bids_query);
$bids_count = mysqli_num_rows($bids_result);

// Fetch assigned projects
$assigned_projects_query = "SELECT * FROM tbl_project_assigned WHERE user_id = $user_id";
$assigned_projects_result = mysqli_query($con, $assigned_projects_query);
$assigned_projects_count = mysqli_num_rows($assigned_projects_result);

// Fetch completed projects
$completed_projects_query = "SELECT * FROM tbl_completion WHERE user_id = $user_id";
$completed_projects_result = mysqli_query($con, $completed_projects_query);
$completed_projects_count = mysqli_num_rows($completed_projects_result);

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

                <div class="row row-cols-1 row-cols-md-3 row-cols-xl-3">
                    <div class="col">
                        <div class="card radius-10 bg-gradient-deepblue">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><?php echo $bids_count; ?></h5>
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
                                    <h5 class="mb-0 text-white"><?php echo $assigned_projects_count; ?></h5>
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
                                    <h5 class="mb-0 text-white"><?php echo $completed_projects_count; ?></h5>
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

                <!-- Projects Summary Section -->
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="mb-0">Assigned Projects Summary</h5>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Project Title</th>
                                        <th>Deadline</th>
                                        <th>Fee</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($assigned_project = mysqli_fetch_assoc($assigned_projects_result)) {
                                        $project_id = $assigned_project['project_id'];
                                        $project_query = "SELECT * FROM tbl_projects WHERE id = $project_id";
                                        $project_result = mysqli_query($con, $project_query);
                                        $project = mysqli_fetch_assoc($project_result);
                                    ?>
                                    <tr>
                                        <td><?php echo $project['project_title']; ?></td>
                                        <td><?php echo $project['project_deadline']; ?></td>
                                        <td>$<?php echo $project['project_fee']; ?></td>
                                        <td><?php echo $project['status']; ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--End Row-->

            </div>
        </div>
        <!--end page wrapper-->

        <?php include "./partials/last_code.php"; ?>
        <?php
        if (isset($_SESSION["success"])) {
            echo "<script>toastr.success('" . $_SESSION["success"] . "');</script>";
        }
        ?>
    </div>
</body>

</html>