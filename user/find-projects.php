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

?>

<!doctype html>
<html lang="en" class="semi-dark">

<head>
    <?php include "./partials/head.php" ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .project-card {
            margin-bottom: 20px;
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-3 col-xl-2">
                                        <a href="add-project.php" class="btn btn-primary mb-3 mb-lg-0"><i class='bx bxs-plus-square'></i>New Project</a>
                                    </div>
                                    <div class="col-lg-9 col-xl-10">
                                        <form class="float-lg-end">
                                            <div class="row row-cols-lg-2 row-cols-xl-auto g-2">
                                                <div class="col">
                                                    <div class="position-relative">
                                                        <input type="text" class="form-control ps-5" placeholder="Search Project..." id="search-project">
                                                        <span class="position-absolute top-50 translate-middle-y"><i class="bx bx-search"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                        <button type="button" class="btn btn-white">Sort By</button>
                                                        <div class="btn-group" role="group">
                                                            <button id="btnGroupDrop1" type="button" class="btn btn-white dropdown-toggle dropdown-toggle-nocaret px-1" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class='bx bx-chevron-down'></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                <li><a class="dropdown-item sort-option" href="#" data-sort="created_at">Posted Date</a></li>
                                                                <li><a class="dropdown-item sort-option" href="#" data-sort="project_fee">Fee</a></li>
                                                                <li><a class="dropdown-item sort-option" href="#" data-sort="project_deadline">Deadline</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fetch All The Projects Here In A Perfect Format Like Freelancer.com or Upwork.com-->
                <div id="project-list" class="row">
                    <!-- Projects will be loaded here via AJAX -->
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

        <script>
            $(document).ready(function() {
                function fetchProjects(sortBy = '') {
                    $.ajax({
                        url: 'fetch_projects.php',
                        type: 'GET',
                        data: {
                            sortBy: sortBy
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.error) {
                                alert(response.error);
                            } else {
                                var projectList = '';
                                $.each(response, function(index, project) {
                                    projectList += `
                                        <div class="col-12 col-md-6 col-lg-4">
                                            <div class="card project-card">
                                                <div class="card-body">
                                                    <h5 class="card-title text-success">${project.name}</h5>
                                                    <h6>${project.project_title}</h6>
                                                    <div class="mb-1 text-muted small">${new Date(project.created_at).toLocaleDateString()}</div>
                                                    <p class="card-text">${project.project_desc.substring(0, 150)}...</p>
                                                    <hr>
                                                    <p><strong>Fee:</strong> ${project.project_fee} ETH</p>
                                                    <p><strong>Deadline:</strong> ${new Date(project.project_deadline).toLocaleDateString()}</p>
                                                    <p><strong>Status:</strong> ${project.status}</p>
                                                    <a href="project-details.php?id=${project.id}" class="btn btn-outline-success">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                });
                                $('#project-list').html(projectList);
                            }
                        }
                    });
                }

                // Initial fetch
                fetchProjects();

                // Sorting functionality
                $('.sort-option').on('click', function(e) {
                    e.preventDefault();
                    var sortBy = $(this).data('sort');
                    fetchProjects(sortBy);
                });

                // Search functionality
                $('#search-project').on('keyup', function() {
                    var searchQuery = $(this).val().toLowerCase();
                    $(".project-card").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(searchQuery) > -1);
                    });
                });
            });
        </script>
    </div>
</body>

</html>