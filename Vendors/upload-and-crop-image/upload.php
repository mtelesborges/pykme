<?php

//upload.php

if(isset($_POST["image"]))
{
	$data = $_POST["image"];

        
        $id = $_POST["id"];
        
        $userType = $_POST["userType"];


	$image_array_1 = explode(";", $data);


	$image_array_2 = explode(",", $image_array_1[1]);

	$data2 = base64_decode($image_array_2[1]);
        
        $key = hash("sha256","".$id."".time()."itstheageofAquarius!@31008");

	$imageName = '/View/upload/'.$userType.'/'.$id.'/'.time().''.$key.''. '.png';
        
        $uploadpath = '../..'.$imageName;

	file_put_contents($uploadpath, $data2);


        echo $imageName;
}

