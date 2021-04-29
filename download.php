<?php

//Including the encryted php
include 'file_encryptor.php';
include 'db_connect.php';

$key = $_POST["keyFile"];

$sql="SELECT * FROM file_details where key_file='$key'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if($num==0 || $num>1)   {
    echo "Key does not exist.";
}
else    {

    // Check expiry
    $row=mysqli_fetch_assoc($result);
    $inserted_time = $row["inserted_time"];
    $file_url = "uploads/".$row["file_name"];
    if ($inserted_time - time() > 86400) {  // more than 24 hours
        unlink($file_url);
        echo 'File expired';
        header('Location: expired.php');
    }

    if(file_exists($file_url . ".enc")) {
        //Decrypting file
        decryptFile($file_url, $dKey);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        readfile($file_url);

        //deleting encryted file and plain text file
        $temp = $file_url . ".enc";
        unlink($file_url);
        unlink($temp);

        //deleting from database
        $sql="DELETE FROM file_details where key_file='$key'" ;
        $result = mysqli_query($conn, $sql);
        if(!$result){
            echo "Deletion in the database is unsuccessful ---> ". mysqli_error($conn);
        }
        die();
    }
    else{
        //http_response_code(404);
	    die("File not found or expired.".$file_url);
    }
}
echo '<br><a href="index.html">Go back</a>';
?>