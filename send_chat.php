<?php 
include("db.php");
    extract($_POST);

    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $message=mysqli_real_escape_string($db,$_POST['chat']);
    $sql = "INSERT INTO chat VALUES (NULL, '$user_id', '$message', NULL)";
    $fetch=$db->query($sql);
?>

