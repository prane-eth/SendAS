<?php
$servername = "sql209.ultimatefreehost.in";  // "sql209.ultimatefreehost.in";
$username = "ltm_28498913";
$password = "i32f5GN4sSLw93pq13";
$dbname = "ltm_28498913_project_db";

/* Online hosting
$servername = "mysql-29585-0.cloudclusters.net:29585";
$username = "root";
$password = "testtest";
$database="project_db";
*/

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn)
    die("Failed: ". mysqli_connect_error());
    // "Sorry we failed to connect: "


/*$sql = "SELECT * FROM test_table";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo $row['Value'] . "<br>";
  }
} else {
  echo "0 results";
}

mysqli_close($conn);*/
/*

to show code used to create table, SHOW CREATE TABLE file_details

CREATE TABLE file_details (
    file_name varchar(150) DEFAULT NULL,
    key_file varchar(150) NOT NULL PRIMARY KEY,
    inserted_time bigint DEFAULT NULL
)
*/

?>
