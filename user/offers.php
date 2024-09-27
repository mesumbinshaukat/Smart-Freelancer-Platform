<?php
session_start();
include("../connection/connection.php");
require __DIR__ . '/partials/fetch_user_details.php';

// Fetch the logged-in user's details
$user_details = get_user_info($_COOKIE["email"], $con);

if (!isset($_COOKIE["email"]) || empty($_COOKIE["email"]) || !isset($_COOKIE["user_logged_in_bool"]) || empty($_COOKIE["user_logged_in_bool"])) {
    $_SESSION["error"] = "Please login first";
    header("location:../login.php");
    exit();
}

// Query to fetch the bids placed by the current user on other projects
$projects_query = "
    SELECT p.id as project_id, p.project_title, p.u_id as creator_id, u.name as creator_name, 
           b.id as bid_id, b.bid_letter, b.bid_date, b.bid_price, 
           b.user_id as bidder_id, bu.name as bidder_name
    FROM tbl_bids b
    JOIN tbl_projects p ON p.id = b.project_id
    JOIN tbl_user u ON p.u_id = u.id
    JOIN tbl_user bu ON b.user_id = bu.id
    WHERE b.user_id = ?
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
                            <table id="data-table" class="table table-striped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Proposal</th>
                                        <th>Offer Date</th>
                                        <th>Offer Price</th>
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
                                            echo "<td>" . htmlspecialchars($row['project_title']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_letter']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_date']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['bid_price']) . " ETH</td>";
                                            echo "<td>" . htmlspecialchars($row['creator_name']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                        <td colspan='6' class='text-center'>You haven't placed any offers yet</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Project Name</th>
                                        <th>Proposal</th>
                                        <th>Offer Date</th>
                                        <th>Offer Price</th>
                                        <th>Project Created By</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("./partials/last_code.php") ?>

</body>

</html>