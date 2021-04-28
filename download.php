<?php
$key = $_POST["keyFile"];

// Connecting to the Database
$servername = "mysql-29500-0.cloudclusters.net:29500/";
$username = "root";
$password = "testtest";
$database="project_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}

$sql="SELECT file_name FROM file_details where key_file='$key'";
$result = mysqli_query($conn, $sql);
$num=mysqli_num_rows($result);
if($num==0 || $num>1){
    echo "Key does not exist.";
}
else{
    $row=mysqli_fetch_assoc($result);
    $file_url = "uploads/".$row["file_name"];
    if(file_exists($file_url)) {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
        readfile($file_url);
        die();
    }
    else{
        http_response_code(404);
	    die();
    }
}
echo '<br><a href="index.html">Go back</a>';
?>