<?php

//Including the encryted php
include 'file_encryptor.php';

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
// Random key generator
function random_str(
  int $length = 64,
  string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
  if ($length < 1) {
      throw new RangeException("Length must be a positive integer");
  }
  $pieces = [];
  $max = mb_strlen($keyspace, '8bit') - 1;
  for ($i = 0; $i < $length; ++$i) {
      $pieces []= $keyspace[random_int(0, $max)];
  }
  return implode('', $pieces);
}

// Function to insert file details and key
function insert_details(string $filename, string $datetime, string $key, object $conn){
  $sql="INSERT INTO file_details(file_name,key_file,inserted_time) VALUES ('$filename','$key','$datetime')";
  $result = mysqli_query($conn, $sql);

  // Check for the database creation success
  if(!$result){
    echo "The insertion of the values failed because of this error ---> ". mysqli_error($conn);
    return false;
  }
  return true;
}

// Function to check for duplicate key
function checkKey(string $key, object $conn){
  $sql="SELECT * FROM file_details where key_file='$key'";
  $result = mysqli_query($conn, $sql);
  $num=mysqli_num_rows($result);
  if($num>=1)
  return false;
  return true;
}

// Update database if the file was uploaded successfully
if($uploadOk != 0){
  // Connecting to the Database
  $servername = "mysql-29585-0.cloudclusters.net:29585/";
  $username = "root";
  $password = "testtest";
  $database="project_db";

  $conn = mysqli_connect($servername, $username, $password, $database);

  if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
  }

  $filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
  $datetime = date("Y-m-d H:i:s");
  do{
  $key = random_str(6);
  }while(!checkKey($key, $conn));

  if(insert_details($filename, $datetime, $key, $conn)){
    echo "<br>Your Key for the file: ".$key;
  }
}
echo '<br><a href="index.html">Go back</a>';
?>
