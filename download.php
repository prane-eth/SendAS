<?php
//destroying bots
if (isset($_SERVER['HTTP_USER_AGENT'])
        && preg_match('/bot|curl|wget|crawl|slurp|spider|mediapartners/i',
        $_SERVER['HTTP_USER_AGENT']) )
    die("Detected as a bot. This site is not for bots.");

//Including the encryted php
include 'file_encryptor.php';
include 'db_connect.php';
include 'functions.php';

$he = "";
$me = "";
$hs = "";
$ms = ""; 

if ($_SERVER["REQUEST_METHOD"]=="POST")
    $key = $_POST["keyFile"];
else
    $key = $_GET["key"];  // use link /download.php?key=a1b2c3 to download file


// validate key to prevent attacks
if (strlen($key)!=6)
    die('Invalid key length <br><a href="index.html">Go back</a>');
if(!ctype_alnum($key)) // if not alphanumeric
    die('Invalid characters in key <br><a href="index.html">Go back</a>');


$sql="SELECT * FROM file_details where key_file='$key'";
$result = mysqli_query($conn, $sql);
$num = mysqli_num_rows($result);
if($num==0 || $num>1)   {
    $he = "Sorry for the inconvenience.";
    $me = "Key does not exist.";
    alertToPage($me,$he);
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

        $hs = "Thank You for using SendFAST.";
        $ms = "Your file ". $row['file_name']. " is downloading.";
        alertToPage($ms, $hs);

        //deleting from database
        $sql="DELETE FROM file_details where key_file='$key'" ;
        $result = mysqli_query($conn, $sql);
        if(!$result){
            $he = "Database Error";
            $me = "Deletion in the database is unsuccessful ---> ". mysqli_error($conn);
            alertToPage($me,$he);
        }
        //die();
    }
    else{
        $he = "Database Error";
        $me = "File not found or expired.".$file_url;
        alertToPage($me,$he);
	    //die(");
    }
}

?>

</body>
</html>