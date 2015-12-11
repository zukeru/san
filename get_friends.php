<?php 
include("db.php");
    extract($_POST);
    session_start();
    $serach_string = $_POST['userid'];
    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$serach_string'");
    $friends = array();
    while ($row = mysqli_fetch_array($fetch)) {
    	$friend_name = $row['friend_id'];
    	$pic_fetch=$db->query("SELECT * FROM profile_images WHERE username='$friend_name'");
    	$profile_pic_url = mysqli_fetch_array($pic_fetch);
    	array_push($row, $profile_pic_url);
        array_push($friends, $row);
    }
    echo json_encode($friends);
?>

