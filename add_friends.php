<?php 
    $db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
    extract($_POST);

    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $friend_id=mysqli_real_escape_string($db,$_POST['friendname']);

    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$user_id' and friend_id=$friend_id");
    $count=mysqli_num_rows($fetch);
    if($count==0):
        $sql = "INSERT INTO friend_requests VALUES (NULL, '$user_id', '$friend_id')";
        if ($db->query($sql)):   
            echo 1;
        else:
            echo "Error: " . $sql . "<br>" . $db->error;        
        endif;
    endif;
?>
