<?php

//upload.php

if(isset($_FILES["images"]))
{
        $id = $_POST["id"];
        $userType = $_POST["userType"];
        // Count total files
        $countfiles = count($_FILES['images']['name']);

        // Upload directory
        $upload_location = "/View/upload/$userType/$id/";

        // To store uploaded files path
        $files_arr = array();

        // Loop all files
        for($index = 0;$index < $countfiles;$index++){

           if(isset($_FILES['images']['name'][$index]) && $_FILES['images']['name'][$index] != ''){
               
              // Get extension
              $ext = strtolower(pathinfo($_FILES['images']['name'][$index], PATHINFO_EXTENSION));
              
              // File name
              $key = hash("sha256","".$id."".time()."itstheageofAquarius!@31008".$_FILES['images']['name'][$index]);
              $filename = $key.".".$ext;
             
              // Valid image extension
              $valid_ext = array("png","jpeg","jpg");

              // Check extension
              if(in_array($ext, $valid_ext)){

                 // File path
                 $path = $upload_location.$filename;
                  
                 // Upload file
                 if(move_uploaded_file($_FILES['images']['tmp_name'][$index],"../..".$path)){
                    $files_arr[] = $path;
                 }
              }
           }
        }
        echo json_encode($files_arr);
        die;
}

