<?php
function alertToPage($me, $he)  {
    session_start();
    // store error in session
    $_SESSION['h'] = $he;
    $_SESSION['m'] = $me;
    header('Location: error.php');
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
?>