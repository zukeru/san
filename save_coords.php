<?php 
    $db = new mysqli('localhost', 'sg0920', 'GZsz@#2010', 'trackingsoftware');
    extract($_POST);

    $user_id=mysqli_real_escape_string($db,$_POST['userid']);
    $geo_lat=mysqli_real_escape_string($db,$_POST['geo_lat']);
    $geo_lon=mysqli_real_escape_string($db,$_POST['geo_lon']);

    $fetch=$db->query("SELECT * FROM locations WHERE user_id='$user_id'");
    $count=mysqli_num_rows($fetch);
    if($count==0):
        $sql = "INSERT INTO locations VALUES (NULL, '$user_id', '$geo_lat', '$geo_lon')";
        if ($db->query($sql)):   
            echo 1;
        else:
            echo "Error: " . $sql . "<br>" . $db->error;        
        endif;
    else:
        $sql = "UPDATE locations SET user_id='$user_id', geo_lat='$geo_lat', geo_lon='$geo_lon' where user_id ='$user_id'";
        if ($db->query($sql)): 
            echo 1;  
        else:
            echo "Error: " . $sql . "<br>" . $db->error;           
        endif;
    endif;
?>
