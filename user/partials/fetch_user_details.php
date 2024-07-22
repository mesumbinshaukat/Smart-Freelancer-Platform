<?php
function get_user_info($user_email, $con)
{

    $query = "SELECT * FROM `tbl_user` WHERE `email` = '$user_email'";
    $run = mysqli_query($con, $query);
    $row = mysqli_fetch_array($run);
    return $row;
}
