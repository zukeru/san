<?php 
include("db.php");
    extract($_POST);
    session_start();
    $serach_string = $_POST['userid'];
    $fetch=$db->query("SELECT * FROM friend_requests WHERE friend_id='$serach_string'");
    $friends = array();
    while ($row = mysqli_fetch_array($fetch)) {
        array_push($friends, $row);
    }
    echo json_encode($friends);
?>

