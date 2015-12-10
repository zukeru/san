<?php 
    $db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
    extract($_POST);
    session_start();
    $user_id=mysqli_real_escape_string($db,$_POST['userid']);

    $fetch=$db->query("SELECT * FROM locations WHERE user_id='$user_id'");
    $count=mysqli_num_rows($fetch);
    if($count==1): 

            $row=mysqli_fetch_array($fetch);
            echo json_encode($row);
            //echo $row['geo_lat'] . $row['geo_lon'] . $row['user_id'];
    else:
            echo "Error: seomthing went wrong with retieving coords.";          
    endif;
?>
