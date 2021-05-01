<!DOCTYPE html>
<html lang="en">
    <title>SendFAST</title>
    <link rel="shortcut icon" href="Images/favicon.ico" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
<body>
  <center><div class="logo"></div></center>
  <p style="font-size: 20px; text-align: center; color: purple;">Send files Anonymously and Securely</p>

<?php

include 'functions.php';

session_start();
// $_SESSION['he'] = $he;
// $_SESSION['he'] = $me;
alert($_SESSION['he'], $_SESSION['me']);
?>