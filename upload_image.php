<?php
$target_dir = "images/";
include("db.php");
$user_name = $_POST['user_name'];
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$image_name = $_FILES["file"]["name"];

$fetch=$db->query("SELECT * FROM profile_images WHERE username='$user_name'");
$count=mysqli_num_rows($fetch);
if($count==0):
		$sql = "INSERT INTO profile_images VALUES (NULL, '$user_name', '$image_name')";
		if ($db->query($sql)):   
		     echo 1;
		else:
		     echo "Error: " . $sql . "<br>" . $db->error;        
		endif;
else:
		$sql = "UPDATE profile_images SET username='$user_name', filename='$image_name' where username ='$user_name'";
		if ($db->query($sql)):   
		     echo 1;
		else:
		     echo "Error: " . $sql . "<br>" . $db->error;        
		endif;
endif;

$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["file"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "JPEG" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
