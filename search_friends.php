<?php 
include("db.php");
    extract($_POST);
    $serach_string = $_POST['searchstring'];
    $friends_list = array();
    $fetch=$db->query("SELECT username FROM users WHERE username LIKE '%$serach_string%'");
    while ($row = mysqli_fetch_array($fetch)) {
    	$friend_name = $row['username'];
    	$pic_fetch=$db->query("SELECT * FROM profile_images WHERE username='$friend_name'");
    	$profile_pic_url = mysqli_fetch_array($pic_fetch);
    	array_push($row, $profile_pic_url);
		array_push($friends_list, $row);
    }
    echo json_encode($friends_list);
?>
