<?php 
include("db.php");
    extract($_POST);
    session_start();
    $search_string = $_POST['userid'];
    $fetch=$db->query("SELECT * FROM friends WHERE user_id='$search_string'");
    $chats = array();
    while ($row = mysqli_fetch_array($fetch)) {
    	$friend_name = $row['friend_id'];
        $chat_fetch=$db->query("SELECT * FROM chat WHERE username='$friend_name' OR username='$search_string'");
        while ($rows = mysqli_fetch_array($chat_fetch)) {

                $check_string = $rows['message'];
                $chat_username = $rows['username'];

                //get profile picture and save to rows array
                $fetch_pic=$db->query("SELECT * FROM profile_images WHERE username='$chat_username'");
                $pic_row=mysqli_fetch_array($fetch_pic);
                array_push($rows, $pic_row);

                if (in_array($check_string, $chats)):
                    //do nothing
                else:
                 array_push($chats, $rows);
                endif;
            } 
    }
    echo json_encode($chats);
?>

