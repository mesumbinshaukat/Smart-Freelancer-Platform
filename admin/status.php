<?php
include("../connection/connection.php");
$user_id = $_GET['user_id'];
$update_query = "UPDATE `tbl_user` SET `status`='1' WHERE `id` = $user_id";
$query_run_update = mysqli_query($con,$update_query);
header('location:users.php');



?>