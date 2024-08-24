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

// Fetch the user's projects
$projects_query = "SELECT * FROM tbl_projects WHERE u_id = ? AND status != 'deleted'";
$stmt = $con->prepare($projects_query);
$stmt->bind_param("i", $user_details['id']);
$stmt->execute();
$result = $stmt->get_result();


// Check if the delete button was clicked
if (isset($_POST['delete']) && isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];

    // Update the project status to 'deleted'
    $update_query = "UPDATE tbl_projects SET status = 'deleted' WHERE id = ? AND u_id = ?";
    $update_stmt = $con->prepare($update_query);
    $update_stmt->bind_param("ii", $project_id, $user_details['id']);

    if ($update_stmt->execute()) {
        $_SESSION["success"] = "Project deleted successfully.";
    } else {
        $_SESSION["error"] = "Failed to delete project.";
    }

    // Redirect to the same page to avoid form resubmission
    header("location: delete-project.php");
    exit();
}

?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
</head>

<body>
    <div class="wrapper">
        <?php include("./partials/sidebar.php"); ?>
        <header>
            <?php include("./partials/navbar.php"); ?>
        </header>
        <div class="page-wrapper">
            <div class="page-content">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="projects-table" class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Title</th>
                                        <th>Project Description</th>
                                        <th>Deadline</th>
                                        <th>Fee (ETH)</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_desc']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_deadline']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_fee']) . "</td>";
                                            echo "<td>
                                                    <form method='post' action='delete-project.php'>
                                                        <input type='hidden' name='project_id' value='" . $row['id'] . "' />
                                                        <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                                                    </form>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No projects found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
    </div>
</body>

</html>