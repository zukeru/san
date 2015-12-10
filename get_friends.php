<?php 
include("db.php");
    extract($_POST);
    session_start();
    $serach_string = $_POST['userid'];
    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$serach_string'");
    $friends=mysqli_fetch_array($fetch);
    echo json_encode($friends);
?>
