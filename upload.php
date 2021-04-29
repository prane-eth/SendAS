<?php

include 'file_encryptor.php';
// include 'db_connect.php';
include 'functions.php';

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$key = "770A8A65DA156D24EE2A093277530142";


// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  } 

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, a file with same already exists.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      //Encrypting file and decrypting it.
      encryptFile($target_file,$key);
      //deleting plain file
      unlink($target_file);
      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

    } else {
      echo "Sorry, there was an error uploading your file.";
      $uploadOk =0;
    }
  }
echo "<br>";

// Update database if the file was uploaded successfully
if($uploadOk != 0){
  include 'db_connect.php';

  $filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
  $datetime = time(); // date("Y-m-d H:i:s");
  do{
  $key = random_str(6);
  }while(!checkKey($key, $conn));

  if(insert_details($filename, $datetime, $key, $conn)){
    echo "<br>Your Key for the file: ".$key;
  }
}
echo '<br><a href="index.html">Go back</a>';
?>