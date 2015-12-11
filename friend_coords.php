<?php 
include("db.php");
    extract($_POST);
    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$user_id'");
    $locations = array();
    while ($row = mysqli_fetch_array($fetch)) {
        $friend_id = $row['friend_id'];
        $fetchs=$db->query("SELECT * FROM locations WHERE user_id='$friend_id'");
        while ($rows = mysqli_fetch_array($fetchs)) {
            array_push($locations, $rows);
        }

    }
    echo json_encode($locations);
?>

