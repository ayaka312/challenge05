<?php

$upload_err = '';

if (isset($_POST['submit'])) {
    $target_dir = "../uploads/";
    $fileName = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_FILENAME);
    $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    $increment = '';
    
    while(file_exists($target_dir. $fileName . $increment . '.' . $fileType)) {
        $increment++;
    }   
    $target_file = $target_dir. $fileName . $increment . '.' . $fileType;

    if ($_FILES["fileToUpload"]["size"] > 50000000000) {
        $upload_err = "Đã xảy ra lỗi!";
    }
    else
    if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
    && $fileType != "gif" && $fileType != "txt" && $fileType != 'pdf') {
        $upload_err = "Đã xảy ra lỗi! Hãy thực hiện lại";
    }
    if (empty($upload_err)) move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file); 
}

if (isset($_POST['sbmExercise'])) {
    $target_dir = "exercise/";
    $fileName = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_FILENAME);
    $fileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    $target_file = $target_dir. $fileName . '.' . $fileType;
    
    if (file_exists($target_file) || $_FILES["fileToUpload"]["size"] > 50000000000 || $fileType != "txt") {
        $upload_err = "Đã xảy ra lỗi! Hãy thực hiện lại.";
    }   

    if (empty($upload_err)) move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file); 
}
  
?>