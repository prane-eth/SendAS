<?php
// Random key generator
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
  ): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
  }
  
  // Function to insert file details and key
  function insert_details(string $filename, int $datetime, string $key, object $conn){
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
?>