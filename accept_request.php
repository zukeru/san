
<?php 
    include("db.php");
    extract($_POST);

    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $friend_id=mysqli_real_escape_string($db,$_POST['friendname']);

    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$user_id' and friend_id=$friend_id");
    $count=mysqli_num_rows($fetch);
    if($count==0):
        $sql = "INSERT INTO friends VALUES (NULL, '$user_id', '$friend_id')";
        if ($db->query($sql)):  
            $sql = "INSERT INTO friends VALUES (NULL, '$friend_id', '$user_id')";
            if ($db->query($sql)):  
                $sql = "DELETE FROM friend_requests WHERE user_id='$friend_id' AND friend_id='$user_id'";
                if ($db->query($sql)):   
                    echo 1;
                endif;
            endif;
        else:
            echo "Error: " . $sql . "<br>" . $db->error;        
        endif;
    endif;
?>
