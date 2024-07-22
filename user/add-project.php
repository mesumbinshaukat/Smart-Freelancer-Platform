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

if (isset($_POST["submit"])) {
	$email = $_COOKIE["email"];

	$select_query = "SELECT * FROM `tbl_user` WHERE `email` = ?";

	$select_stmt = $con->prepare($select_query);

	$select_stmt->bind_param("s", $email);

	$select_stmt->execute();

	$result = $select_stmt->get_result();

	$row = $result->fetch_assoc();

	$title = htmlspecialchars($_POST["title"]);
	$desc = htmlspecialchars($_POST["desc"]);
	$deadline = htmlspecialchars($_POST["deadline"]);
	$fee = (string) htmlspecialchars($_POST["fee"]);
	$attachments = $_FILES["attachments"]["name"];
	$attachments_tmp = $_FILES["attachments"]["tmp_name"];
	$cat_id = htmlspecialchars($_POST["niche"]);
	$status = "Not Awarded";

	$custom_attachment_name = uniqid() . "_" . $attachments;

	$insert_query = "INSERT INTO `tbl_projects`(`project_title`, `project_desc`, `project_deadline`, `project_fee`, `attachments`, `cat_id`, `u_id`, `status`) VALUES (?,?,?,?,?,?,?,?)";

	$stmt = $con->prepare($insert_query);

	$stmt->bind_param("ssssssss", $title, $desc, $deadline, $fee, $custom_attachment_name, $cat_id, $row["id"], $status);
	if ($stmt->execute()) {
		move_uploaded_file($attachments_tmp, "./assets/attachments/" . $custom_attachment_name);
		$_SESSION["success"] = "Project created successfully";
		header("location:add-project.php");
		exit();
	} else {
		$_SESSION["error"] = "Something went wrong";
		header("location:add-project.php");
		exit();
	}
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
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h6 class="mb-0 text-uppercase">Create New Project</h6>
                        <hr />
                        <form method="post" class="row g-3" enctype="multipart/form-data">
                            <div class="card">
                                <div class="card-body">
                                    <label class="form-label form-control">Project Title <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg mb-3" type="text"
                                        placeholder="Project Title" name="title" required>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Project Description <span
                                            class="text-danger">*</span></label>
                                    <textarea name="desc" class="form-control form-control-lg mb-3"
                                        placeholder="Project Description" required></textarea>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Deadline <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg mb-3" type="date" name="deadline"
                                        required>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Project Fee <span
                                            class="text-primary">(ETH)</span><span class="text-danger">*</span></label>
                                    <input class="form-control form-control-lg mb-3" type="string"
                                        placeholder="Project Fee In Etherium" name="fee" required>
                                </div>

                                <div class="card-body">
                                    <label class="form-label form-control">Attachments <span
                                            style="color: blue;">(Optional)</span></label>
                                    <!-- <input class="form-control form-control-lg mb-3" type="file" name="attachments"> -->


                                    <input id="image-uploadify" type="file" name="attachments"
                                        accept=".xlsx,.xls,image/*,.doc,audio/*,.docx,video/*,.ppt,.pptx,.txt,.pdf"
                                        multiple>

                                </div>

                                <div class="card-body">
                                    <?php
									$select_query = "SELECT * FROM `tbl_niche`";
									$select_stmt = $con->prepare($select_query);
									$select_stmt->execute();
									$select_stmt->store_result();
									$select_stmt->bind_result($id, $niche_name);
									?>
                                    <label class="form- form-control">Select Niche <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg mb-3" name="niche" required>
                                        <option value="" selected disabled>Select Niche</option>
                                        <?php
										while ($select_stmt->fetch()) {
											echo "<option value='$id'>$niche_name</option>";
										}
										?>
                                    </select>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary" name="submit">Create New Project</button>
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