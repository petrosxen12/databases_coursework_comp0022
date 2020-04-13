<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
if (getenv('APPSETTING_env') == true) {
    header("location: /dist/logout.php");
    exit;
}

header("location: /cwrepo/dist/login.php");
exit;
