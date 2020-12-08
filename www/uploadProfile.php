<?php
	require './connect.php';
    header('Access-Control-Allow-Origin: *');
	$target_dir = "Profile/";
	$target_file = $target_dir . basename($_FILES["photo"]["name"]);
	$uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // $myfile = fopen("./Images/non.txt", "w") or die("Unable to open file!");
    // $txt = "Upload this file : ";
    // fwrite($myfile, $txt);
    // $txt = $_FILES["photo"]["tmp_name"];
    // fwrite($myfile, $txt);
    // $txt = "\nto this file : ";
    // fwrite($myfile, $txt);
    // $txt = $target_file;
    // fwrite($myfile, $txt);
    // fclose($myfile);

	$check = getimagesize($_FILES["photo"]["tmp_name"]);
	if($check !== false) {
		// echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
		if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
			echo "$target_file";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}
?>