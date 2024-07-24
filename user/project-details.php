<?php
session_start();
include("../connection/connection.php");

if (!isset($_GET['id'])) {
    $_SESSION["error"] = "Invalid project ID";
    header("location: find-projects.php");
    exit();
}

$project_id = $_GET['id'];

require __DIR__ . '/partials/fetch_user_details.php';
$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

$query = $con->prepare("SELECT * FROM tbl_projects WHERE id = ?");
$query->bind_param("i", $project_id);
$query->execute();
$project = $query->get_result()->fetch_assoc();

if (!$project) {
    $_SESSION["error"] = "Project not found";
    header("location: find-projects.php");
    exit();
}

$is_creator = $project['u_id'] == $user_details['id'];

// Check if the user has already placed a bid for this project
$bid_check_query = $con->prepare("SELECT COUNT(*) as bid_count FROM tbl_bids WHERE user_id = ? AND project_id = ?");
$bid_check_query->bind_param("ii", $user_details['id'], $project_id);
$bid_check_query->execute();
$bid_check_result = $bid_check_query->get_result()->fetch_assoc();
$has_bid = $bid_check_result['bid_count'] > 0;

// Fetch related projects excluding those posted by the current user
$related_projects_query = $con->prepare("
    SELECT * FROM tbl_projects 
    WHERE cat_id = ? 
    AND id != ? 
    AND u_id != ?
");
$related_projects_query->bind_param("iii", $project['cat_id'], $project_id, $user_details['id']);
$related_projects_query->execute();
$related_projects = $related_projects_query->get_result()->fetch_all(MYSQLI_ASSOC);

?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
    <style>
    .modal-dark .modal-content {
        background-color: #2c2c2c;
        color: #fff;
    }

    .color-indigators .color-indigator-item {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
    }
    </style>
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
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo htmlspecialchars($project['project_title']); ?></h4>
                                <div class="d-flex gap-3 py-3">
                                    <div class="cursor-pointer">
                                        <i class='bx bxs-star text-warning'></i>
                                        <i class='bx bxs-star text-warning'></i>
                                        <i class='bx bxs-star text-warning'></i>
                                        <i class='bx bxs-star text-warning'></i>
                                        <i class='bx bxs-star text-secondary'></i>
                                    </div>
                                    <div class="text-muted">No reviews yet</div>
                                    <div class="text-success"><i class='bx bxs-cart-alt align-middle'></i> No orders yet
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <span class="price h4"><?php echo htmlspecialchars($project['project_fee']); ?>
                                        ETH</span>
                                    <span class="text-muted">/fixed price</span>
                                </div>
                                <p class="card-text fs-6">
                                    <?php echo nl2br(htmlspecialchars($project['project_desc'])); ?></p>
                                <dl class="row">
                                    <dt class="col-sm-3">Created At</dt>
                                    <dd class="col-sm-9">
                                        <?php echo date('F j, Y', strtotime($project['created_at'])); ?></dd>
                                    <dt class="col-sm-3">Deadline</dt>
                                    <dd class="col-sm-9 ">
                                        <?php echo date('F j, Y', strtotime($project['project_deadline'])); ?></dd>
                                    <dt class="col-sm-3">Status</dt>
                                    <dd class="col-sm-9"><?php echo htmlspecialchars($project['status']); ?></dd>
                                </dl>
                                <hr>
                                <?php if (!$is_creator) : ?>
                                <?php if ($has_bid) : ?>
                                <button type="button" class="btn btn-secondary" id="bidButton" disabled>You've already
                                    placed a bid</button>
                                <?php else : ?>
                                <button type="button" class="btn btn-primary" id="bidButton" data-bs-toggle="modal"
                                    data-bs-target="#bidModal">Bid Now</button>
                                <?php endif; ?>
                                <?php endif; ?>
                                <a href="user-profile.php?id=<?php echo $project['u_id']; ?>" class="btn btn-info">View
                                    Client Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bid Modal -->
                <div class="modal fade" id="bidModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                                <h5 class="modal-title text-white">Place Your Bid</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-white">
                                <form id="bidForm">
                                    <div class="mb-3">
                                        <label for="bidPrice" class="form-label">Bid Price (ETH)</label>
                                        <input type="number" class="form-control" id="bidPrice" name="bid_price"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bidLetter" class="form-label">Bid Letter</label>
                                        <textarea class="form-control" id="bidLetter" name="bid_letter" rows="4"
                                            required></textarea>
                                    </div>
                                    <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user_details['id']; ?>">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-dark" id="submitBid">Submit Bid</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Projects -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Related Projects</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($related_projects as $related_project) : ?>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <?php echo htmlspecialchars($related_project['project_title']); ?></h5>
                                        <p class="card-text">
                                            <?php echo htmlspecialchars($related_project['project_desc']); ?></p>
                                        <a href="project-details.php?id=<?php echo $related_project['id']; ?>"
                                            class="btn btn-primary">View Project</a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!--end page wrapper -->
        <?php include "./partials/last_code.php"; ?>
    </div>
    <!--end wrapper-->

    <script>
    $(document).ready(function() {
        $('#submitBid').on('click', function() {
            var form = $('#bidForm');
            $.ajax({
                url: 'submit_bid.php',
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    console.log('Raw response:', response);
                    try {
                        var result = JSON.parse(response);
                        console.log('Parsed result:', result);
                        if (result.success) {
                            $('#bidModal').modal('hide');
                            toastr.success('Bid placed successfully');
                            // Disable bid button and update text
                            $('#bidButton').prop('disabled', true).removeClass(
                                'btn-primary').addClass('btn-secondary').text(
                                "You've already placed a bid");
                        } else {
                            toastr.error(result.error);
                        }
                    } catch (e) {
                        $('#bidModal').modal('hide');
                        toastr.success('Bid placed successfully');
                        // Disable bid button and update text
                        $('#bidButton').prop('disabled', true).removeClass('btn-primary')
                            .addClass('btn-secondary').text("You've already placed a bid");
                    }
                },
                error: function() {
                    toastr.error('An error occurred while processing your request.');
                }
            });
        });
    });
    </script>

    <?php
    if (isset($_SESSION["success"])) {
        echo "<script>
            toastr.success('" . $_SESSION["success"] . "');
        </script>";
        unset($_SESSION["success"]);
    } elseif (isset($_SESSION["error"])) {
        echo "<script>
            toastr.error('" . $_SESSION["error"] . "');
        </script>";
        unset($_SESSION["error"]);
    }
    ?>
</body>

</html>