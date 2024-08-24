<?php
ob_start();
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
$projects_query = "SELECT id, project_title, project_desc, project_deadline, project_fee, status, attachments FROM tbl_projects WHERE u_id = ?";
$stmt = $con->prepare($projects_query);
$stmt->bind_param("i", $user_details['id']);
$stmt->execute();
$result = $stmt->get_result();

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
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0 text-uppercase">Your Projects</h6>
                        <hr />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Deadline</th>
                                        <th>Fee (ETH)</th>
                                        <th>Status</th>
                                        <th>Attachments</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_desc']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_deadline']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_fee']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                            echo "<td>";
                                            if (!empty($row['attachments'])) {
                                                echo "<a href='./assets/attachments/" . htmlspecialchars($row['attachments']) . "' target='_blank'>View Attachment</a>";
                                            } else {
                                                echo "No Attachments";
                                            }
                                            echo "</td>";
                                            echo "<td><button class='btn btn-primary edit-project-btn' data-project-id='" . $row['id'] . "' data-title='" . htmlspecialchars($row['project_title']) . "' data-desc='" . htmlspecialchars($row['project_desc']) . "' data-deadline='" . htmlspecialchars($row['project_deadline']) . "' data-fee='" . htmlspecialchars($row['project_fee']) . "' data-status='" . htmlspecialchars($row['status']) . "' data-attachments='" . htmlspecialchars($row['attachments']) . "'>Update</button></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No projects found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->

        <!-- Edit Project Modal -->
        <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProjectForm" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="project_id" id="project_id">
                            <div class="mb-3">
                                <label for="title" class="form-label">Project Title</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="desc" class="form-label">Project Description</label>
                                <textarea class="form-control" name="desc" id="desc" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="deadline" class="form-label">Deadline</label>
                                <input type="date" class="form-control" name="deadline" id="deadline" required>
                            </div>
                            <div class="mb-3">
                                <label for="fee" class="form-label">Project Fee (ETH)</label>
                                <input type="text" class="form-control" name="fee" id="fee" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="Not Awarded">Not Awarded</option>
                                    <option value="Awarded">Awarded</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="attachments" class="form-label">Attachments</label>
                                <input type="file" class="form-control" name="attachments" id="attachments"
                                    accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf">
                            </div>
                            <button type="submit" class="btn btn-primary" name="update">Update Project</button>
                        </form>
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
    <!--end wrapper-->

    <script>
    // Handle the click event for the "Update" button
    $('.edit-project-btn').click(function() {
        const projectId = $(this).data('project-id');
        const title = $(this).data('title');
        const desc = $(this).data('desc');
        const deadline = $(this).data('deadline');
        const fee = $(this).data('fee');
        const status = $(this).data('status');
        const attachments = $(this).data('attachments');

        $('#project_id').val(projectId);
        $('#title').val(title);
        $('#desc').val(desc);
        $('#deadline').val(deadline);
        $('#fee').val(fee);
        $('#status').val(status);

        $('#editProjectModal').modal('show');
    });
    </script>
</body>

</html>

<?php
// Handle the update project form submission
if (isset($_POST['update'])) {
    $project_id = $_POST['project_id'];
    $title = htmlspecialchars($_POST['title']);
    $desc = htmlspecialchars($_POST['desc']);
    $deadline = htmlspecialchars($_POST['deadline']);
    $fee = (string) htmlspecialchars($_POST['fee']);
    $status = htmlspecialchars($_POST['status']);
    $attachments = $_FILES["attachments"]["name"];
    $attachments_tmp = $_FILES["attachments"]["tmp_name"];

    if (!empty($attachments)) {
        $custom_attachment_name = uniqid() . "_" . $attachments;
        move_uploaded_file($attachments_tmp, "./assets/attachments/" . $custom_attachment_name);
    } else {
        $custom_attachment_name = null;
    }

    if ($custom_attachment_name) {
        $update_query = "UPDATE `tbl_projects` SET `project_title`=?, `project_desc`=?, `project_deadline`=?, `project_fee`=?, `status`=?, `attachments`=? WHERE `id`=?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssssssi", $title, $desc, $deadline, $fee, $status, $custom_attachment_name, $project_id);
    } else {
        $update_query = "UPDATE `tbl_projects` SET `project_title`=?, `project_desc`=?, `project_deadline`=?, `project_fee`=?, `status`=? WHERE `id`=?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("sssssi", $title, $desc, $deadline, $fee, $status, $project_id);
    }

    if ($stmt->execute()) {
        $_SESSION["success"] = "Project updated successfully";
    } else {
        $_SESSION["error"] = "Failed to update project";
    }

    header("location:edit-project.php");
    exit();
}

ob_end_flush(); // Flush the output buffer
?>