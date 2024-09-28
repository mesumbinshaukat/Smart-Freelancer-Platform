<?php
session_start();
include("../connection/connection.php");

if (!isset($_COOKIE["login_type"]) || !isset($_COOKIE["login_checker"])) {
    $_SESSION["error"] = "Please login first";
    header("location:login.php");
    exit();
}

// Fetch all niches from the database
$niches_query = "SELECT * FROM tbl_niche";
$niches_result = mysqli_query($con, $niches_query);

// Handle delete action
if (isset($_GET['delete_id'])) {
    $niche_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM tbl_niche WHERE id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $niche_id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Niche deleted successfully";
    } else {
        $_SESSION["error"] = "Failed to delete niche";
    }
    header("location:edit_niche.php");
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
                    <div class="breadcrumb-title pe-3">Niche</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="index.php"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Niches</li>
                            </ol>
                        </nav>
                    </div>

                </div>
                <!--end breadcrumb-->
                <div class="row">
                    <div class="col-xl-12 mx-auto">
                        <h6 class="mb-0 text-uppercase">Niche List</h6>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Niche Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($niche = mysqli_fetch_assoc($niches_result)) {
                                        echo "<tr>";
                                        echo "<td>" . $niche['id'] . "</td>";
                                        echo "<td>" . $niche['cat_name'] . "</td>";
                                        echo "<td>";
                                        echo "<a href='edit-niche.php?edit_id=" . $niche['id'] . "' class='btn btn-primary btn-sm'>Edit</a> ";
                                        echo "<a href='edit_niche.php?delete_id=" . $niche['id'] . "' class='btn btn-danger btn-sm' onclick=\"return confirm('Are you sure you want to delete this niche?');\">Delete</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <a href="create_niche.php" class="btn btn-secondary">Back to Create Niche</a>

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