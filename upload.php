<!DOCTYPE html>
<html lang="en">
    <title>SendAS</title>
    <link rel="shortcut icon" href="Images/favicon.ico" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
<body>
  <center><div class="logo"></div></center>
  <p style="font-size: 20px; text-align: center; color: purple;">Send files Anonymously and Securely</p>


<?php

if (isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|curl|wget|crawl|slurp|spider|mediapartners/i',
        $_SERVER['HTTP_USER_AGENT']) )
    die("Detected as a bot. This site is not for bots.");

//Including the encryted php
include 'file_encryptor.php';
include 'db_connect.php';
include 'functions.php';

$target_file = "uploads/" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$he = "";
$me = "";
$hs = "";
$ms = ""; 
// Check if file already exists
if (file_exists($target_file) or $uploadOk == 0) {
  $he = "Sorry for the inconvenience.";
  $me = "There's a problem in your file or can you please change the File name and try again.";
  alert($me,$he);
  $uploadOk = 0;
}
else {  // Check if $uploadOk is set to 0 by an error
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    do{
      $key = random_str(6);
    }while(!checkKey($key, $conn));

    //Encrypting file and decrypting it.
    encryptFile($target_file, $key);

    //deleting plain file
    unlink($target_file);

    //Creating a message
    $hs = "Thank You for using SendAS.";
    $ms = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

  } else {
    $he = "Sorry for the inconvenience.";
    $me = "There was an error uploading your file.";
    alert($me,$he);
    $uploadOk =0;
  }
}

// Update database if the file was uploaded successfully
if($uploadOk != 0){
  $filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
  $datetime = time();

  if(insert_details($filename, $datetime, $key, $conn)){
    $ms = $ms."<br>Your Key for the file: <span style='color: purple;'>".$key."</span><br>Copy the Link : <a href='./download.php?key=$key'> Download link </a>";
    alert($ms, $hs);
  }
}
?>

</body>
</html>