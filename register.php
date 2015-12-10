<?php 
$db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
session_start();
    if($_POST['usernamereg']!="" && $_POST['passwordreg']!=""):
        extract($_POST);
        $username=mysqli_real_escape_string($db,$_POST['usernamereg']);
        $email=mysqli_real_escape_string($db,$_POST['email']);
        $name=mysqli_real_escape_string($db,$_POST['name']);
        $pass_encrypt=md5(mysqli_real_escape_string($db,$_POST['passwordreg']));
        $sql = "INSERT INTO users VALUES (NULL, '$email', '$username', '$pass_encrypt', '$name')";
        if ($db->query($sql)):
               $_SESSION['login_username']=$username;    
               echo 1;  
        else:
            echo "Error: " . $sql . "<br>" . $db->error;
            //echo 0;
            
        endif;
    else :
        header("Location:index.php");
    endif;
?>