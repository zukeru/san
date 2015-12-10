<?php 
include("db.php");
    extract($_POST);
    $serach_string = $_POST['searchstring'];
    $fetch=$db->query("SELECT username FROM users WHERE username LIKE '%$serach_string%'");
    $friends_list = array();
    while ($friends=mysqli_fetch_array($fetch)){
        $row=mysqli_fetch_array($fetch);
        array_push($friends_list, $row);
    }
    echo json_encode($friends_list);
?>