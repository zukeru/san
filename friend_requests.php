<?php 
    $db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
    extract($_POST);
    session_start();
    $serach_string = $_POST['userid'];
    $fetch=$db->query("SELECT * FROM friend_requests WHERE friend_id='$serach_string'");
    $friends=mysqli_fetch_array($fetch);
    //echo $search_string
    echo json_encode($friends);
?>
