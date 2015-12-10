<?php 
$db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
session_start();
$check=$_SESSION['login_username'];
if(!isset($check))
{
    header("Location:index.php");
} 
?>