<?php

// This file is to prevent access to /uploads folder
// If user enters <url>/uploads in url, it shows all files. privacy will be problem.

// redirect to previous page
// https://stackoverflow.com/questions/5285031/back-to-previous-page-with-header-location-in-php
header('Location: ' . $_SERVER['HTTP_REFERER']);

?>