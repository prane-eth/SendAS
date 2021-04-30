<?php

//including html
echo '<!DOCTYPE html>';
echo '<html lang="en">';
echo '    <title>SendAS</title>';
echo '    <link rel="shortcut icon" href="Images/favicon.ico" />';
echo '    <meta charset="utf-8">';
echo '    <meta name="viewport" content="width=device-width, initial-scale=1">';
echo '    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">';
echo '    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>';
echo '    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>';
echo '    <link rel="stylesheet" href="style.css">';
echo '<body>';
echo '  <center><div class="logo"></div></center>';
echo '  <p style="font-size: 20px; text-align: center; color: purple;">Send files Anonymously and Securely</p>';

if (isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|curl|wget|crawl|slurp|spider|mediapartners/i',
        $_SERVER['HTTP_USER_AGENT']) )
    die("Detected as a bot. This site is not for bots.");

//Including the encryted php
include 'file_encryptor.php';
include 'db_connect.php';

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
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

// Check if $uploadOk is set to 0 by an error
 else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //Encrypting file and decrypting it.
    encryptFile($target_file,$dKey);

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
    $he = "Database Error";
    $me = "The insertion of the values failed because of this error ---> ". mysqli_error($conn);
    alert($me,$he);
    return false;
  }
  return true;
}
//Function to create a Alert Box
function alert(string $msg, string $head){
  //echo '<div class="modal" id="myModal" role="dialog">';
  echo '<div class="modal-dialog" id="alertbox">';
  echo '  <div class="modal-content">';
  echo '    <div class="modal-header">';
  echo '      <button type="button" class="close" data-dismiss="modal" onclick="location.href = \'/\';">&times;</button>';
  echo '      <h4 class="modal-title">'.$head.'</h4>';
  echo '    </div>';
  echo '    <div class="modal-body">';
  echo '      <p>'.$msg.'</p>';
  echo '    </div>';
  echo '    <div class="modal-footer">';
  echo '      <button type="button" class="btn btn-default" style="background-color: purple;color: white;" data-dismiss="modal" onclick="location.href = \'/\';">Close</button>';
  echo '        </div></div></div>';
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

  $filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
  $datetime = time();
  do{
  $key = random_str(6);
  }while(!checkKey($key, $conn));

  if(insert_details($filename, $datetime, $key, $conn)){
    $ms = $ms."<br>Your Key for the file: <span style='color: purple;'>".$key."</span><br>Copy the Link : <a href='/download.php?key=$key'> Download link </a>";
    alert($ms, $hs);
  }
}

echo '  </body>';
echo ' </html>';
?>