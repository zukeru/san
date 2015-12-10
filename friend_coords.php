<?php 
include("db.php");
    extract($_POST);
    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$user_id'");
    $friends_list = [];
    while ($friends=mysqli_fetch_array($fetch)){
        $friend_id = $friends['friend_id'];
        $fetch=$db->query("SELECT * FROM locations WHERE user_id='$friend_id'");
        $row=mysqli_fetch_array($fetch);
        array_push($friends_list, $row);
    }
    echo json_encode($friends_list);
?>
