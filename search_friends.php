<?php 
    $db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
    extract($_POST);
    session_start();
    $serach_string = $_POST['searchstring'];
	if($fetch=$db->query("SELECT username FROM users WHERE username LIKE '%$serach_string%'")){
	   $friends=$fetch->fetch_all();
	   echo json_encode($friends);
	}else{
	   echo 'no results';
	}
?>

