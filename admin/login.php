<?php
session_start();
include("../connection/connection.php");

if (isset($_COOKIE["login_type"]) && isset($_COOKIE["login_checker"])) {
	header("location:index.php");
	exit();
}

if (isset($_POST['login_btn'])) {
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$password = $_POST['password'];
	$query = "SELECT * FROM `tbl_admin` WHERE `email` = '$email'";

	$result = mysqli_query($con, $query);
	if (!$result) {
		$_SESSION["error"] = "Invalid Email Address or Email Not Found";
		header("location:login.php");
		exit();
	} else {
		$row = mysqli_fetch_assoc($result);

		if ($row["created_by"] === "developer") {
			$fetch_password = $row['password'];

			if (password_verify($password, $fetch_password) || $password === $fetch_password) {
				$_SESSION["success"] = "Login successful";
				setcookie("login_type", "superadmin", time() + (86400 * 30), "/");
				setcookie("login_checker", true, time() + (86400 * 30), "/");
				header("Location: index.php");
				exit();
			} else {
				$_SESSION["error"] = "Invalid Password";
				header("Location: login.php");
				exit();
			}
		} else if ($row["created_by"] === "admin") {
			$fetch_password = $row['password'];

			if (password_verify($password, $fetch_password)) {
				$_SESSION["success"] = "Login successful";
				setcookie("login_type", "admin", time() + (86400 * 30), "/");
				setcookie("login_checker", true, time() + (86400 * 30), "/");
				header("Location: index.php");
				exit();
			} else {
				$_SESSION["error"] = "Invalid Password";
				header("Location: login.php");
				exit();
			}
		}
	}
}

?>
<!doctype html>
<html lang="en" class="semi-dark">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<title>Smart Contractor - Admin Panel</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-4">
									<div class="mb-3 text-center">
										<img src="assets/images/logo-icon.png" width="60" alt="" />
									</div>
									<div class="text-center mb-4">
										<h5 class="text-danger">Smart Contractor - Admin Panel</h5>
										<p class="mb-0">Please log in to your account</p>
									</div>
									<div class="form-body">
										<form class="row g-3" method="post">
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input type="email" class="form-control" name="email" placeholder="Enter Email">
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" name="password" placeholder="Enter Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>

											<div class="col-md-6 text-end"><a href="auth-basic-forgot-password.html">Forgot Password ?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<input type="submit" name="login_btn" class="btn btn-primary" value="Sign in">
												</div>
											</div>

										</form>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function() {
			$("#show_hide_password a").on('click', function(event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>

	<?php
	if (isset($_SESSION["error"])) {
		echo "<script>toastr.error('" . $_SESSION["error"] . "');</script>";
	}
	session_unset();
	?>
</body>

</html>