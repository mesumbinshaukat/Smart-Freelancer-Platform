<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

require __DIR__ . '/partials/fetch_user_details.php';
$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION["error"] = "Client ID is missing";
    header("location:./index.php");
    exit();
}

$client_id = (int) $_GET['id'];

// Fetch client details from tbl_user
$client_query = $con->prepare("SELECT * FROM tbl_user WHERE id = ?");
$client_query->bind_param("i", $client_id);
$client_query->execute();
$client_details = $client_query->get_result()->fetch_assoc();

if (!$client_details) {
    $_SESSION["error"] = "Client not found";
    header("location:./index.php");
    exit();
}

// Fetch projects posted by the client
$projects_query = $con->prepare("SELECT id, project_title FROM tbl_projects WHERE u_id = ?");
$projects_query->bind_param("i", $client_id);
$projects_query->execute();
$client_projects = $projects_query->get_result()->fetch_all(MYSQLI_ASSOC);

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
                <!--start breadcrumb-->
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Client Profile</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Client Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!--end breadcrumb-->
                <div class="container">
                    <div class="main-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex flex-column align-items-center text-center">
                                            <img src="assets/images/avatars/user.png" alt="Client"
                                                class="rounded-circle p-1 bg-primary" width="110">
                                            <div class="mt-3">
                                                <h4><?php echo htmlspecialchars($client_details['name']); ?></h4>

                                                <p class="text-muted font-size-sm">
                                                    <?php echo empty($client_details) ? htmlspecialchars($client_details['address']) : 'N/A'; ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Full Name</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php echo htmlspecialchars($client_details['name']); ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Email</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php echo htmlspecialchars($client_details['email']); ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-sm-3">
                                                <h6 class="mb-0">Phone</h6>
                                            </div>
                                            <div class="col-sm-9 text-secondary">
                                                <?php echo empty($client_details['phone']) ? 'N/A' : htmlspecialchars($client_details['phone']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="d-flex align-items-center mb-3">Projects Posted</h5>
                                        <?php if (count($client_projects) > 0) : ?>
                                        <?php foreach ($client_projects as $project) : ?>
                                        <div class="mb-3">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($project['project_title']); ?>
                                            </h6>
                                            <a href="project-details.php?id=<?php echo $project['id']; ?>"
                                                class="btn btn-primary mt-2">View Project</a>
                                        </div>
                                        <hr class="my-2" />
                                        <?php endforeach; ?>
                                        <?php else : ?>
                                        <p class="text-secondary">No projects posted yet.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
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