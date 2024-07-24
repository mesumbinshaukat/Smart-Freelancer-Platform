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

$projects_query = "
    SELECT p.id as project_id, p.project_title, p.u_id as creator_id, u.name as creator_name, b.id as bid_id, b.bid_letter, b.bid_date, b.bid_price, b.user_id as bidder_id, bu.name as bidder_name
    FROM tbl_projects p
    JOIN tbl_bids b ON p.id = b.project_id
    JOIN tbl_user u ON p.u_id = u.id
    JOIN tbl_user bu ON b.user_id = bu.id
    WHERE p.u_id = ?
";
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
						<div class="table-responsive">
							<table id="example2" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>#</th>
										<th>Contractor Name</th>
										<th>Proposal</th>
										<th>Offer Date</th>
										<th>Offer Price</th>
										<th>Project Name</th>
                                        <th>Project Created By</th>
									</tr>
								</thead>
								<tbody>

                                <?php
                                    if ($result->num_rows > 0) {
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bidder_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['creator_name']) . "</td>";
                                            
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No offers found</td></tr>";
                                    }
                                    ?>


                                <!--  -->
								</tbody>
								
							</table>
						</div>
					</div>
				</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--end page wrapper -->
  </div>
  <!--end wrapper-->

  <!-- Chat Modal -->
  <div class="modal fade" id="chatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-dark">
        <div class="modal-header">
          <h5 class="modal-title text-white">Chat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-white" id="chatBody">
          <!-- Chat messages will be appended here -->
        </div>
        <div class="modal-footer">
          <input type="file" id="chatAttachment" accept=".zip,image/*" style="display:none;">
          <button type="button" class="btn btn-light" onclick="document.getElementById('chatAttachment').click();">Attach</button>
          <input type="text" class="form-control" id="chatMessage" placeholder="Type message">
          <button type="button" class="btn btn-dark" onclick="sendMessage()">Send</button>
        </div>
      </div>
    </div>
  </div>

  <?php include "./partials/last_code.php"; ?>
  
  <script type="module" src="chat.js"></script>
  <script>
    const userId = <?php echo json_encode($user_details['id']); ?>;
  </script>
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

<style>
  .modal-dark .modal-content {
    background-color: #2c2c2c;
    color: #fff;
  }
</style>