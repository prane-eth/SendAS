<?php

// Connecting to the Database
$servername = "mysql-29585-0.cloudclusters.net:29585";
$username = "root";
$password = "testtest";
$database="project_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn){
    die("Sorry we failed to connect: ". mysqli_connect_error());
}

?>