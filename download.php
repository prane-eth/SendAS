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

//destroying bots
if (isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|curl|wget|crawl|slurp|spider|mediapartners/i',
        $_SERVER['HTTP_USER_AGENT']) )
    die("Detected as a bot. This site is not for bots.");

//Including the encryted php
include 'file_encryptor.php';
include 'db_connect.php';

$he = "";
$me = "";
$hs = "";
$ms = ""; 

if ($_SERVER["REQUEST_METHOD"]=="POST")
    $key = $_POST["keyFile"];
else
    $key = $_GET["key"];  // use link /download.php?key=a1b2c3 to download file


$sql="SELECT * FROM file_details where key_file='$key'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if($num==0 || $num>1)   {
    $he = "Sorry for the inconvenience.";
    $me = "Key does not exist.";
    alert($me,$he);
}
else    {
    // Check expiry
    $row = mysqli_fetch_assoc($result);
    $inserted_time = $row["inserted_time"];
    $file_url = "uploads/" . $row["file_name"];
    if (time() - $inserted_time > 86400) {  // 24 hours is 86400 - test using 15
        // if more than 24 hours since upload, file is expired
        unlink($file_url . ".enc");  // delete encrypted file
        header('Location: expired.html');
    }

    if(file_exists($file_url . ".enc")) {
        // Decrypting file
        decryptFile($file_url, $dKey);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        readfile($file_url);

        //deleting encryted file and plain text file
        $temp = $file_url . ".enc";
        unlink($file_url);
        unlink($temp);

        $hs = "Thank You for using SendAS.";
        $ms = "Your file ". $row['file_name']. " is downloading.";
        alert($ms, $hs);

        //deleting from database
        $sql="DELETE FROM file_details where key_file='$key'" ;
        $result = mysqli_query($conn, $sql);
        if(!$result){
            $he = "Database Error";
            $me = "Deletion in the database is unsuccessful ---> ". mysqli_error($conn);
            alert($me,$he);
        }

        
        //die();
    }
    else{
        $he = "Database Error";
        $me = "File not found or expired.".$file_url;
        alert($me,$he);
	    //die(");
    }
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
    echo '      <button type="button" class="btn btn-default" style="background-color: white; color: purple;" data-dismiss="modal" onclick="location.href = \'/\';">Close</button>';
    echo '        </div></div></div>';
  }

  echo '  </body>';
  echo ' </html>';
?>