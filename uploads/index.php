<!-- 
Explanation: Why this page?
This file is for security purpose, to prevent access to /uploads folder
If user enters <url>/uploads in url, it shows all files.
There will be privacy and security issues

Solution is to redirect to previous page
-->

<?php
// https://stackoverflow.com/questions/5285031/back-to-previous-page-with-header-location-in-php
// header('Location: ' . $_SERVER['HTTP_REFERER']);
?>

<script>
    window.location.href='index.html';  // redirect to homepage
</script>
