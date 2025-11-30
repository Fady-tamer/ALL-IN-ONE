<?php
session_start();

// Destroy all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect back to the login page (index.php)
header("Location: ../index.php");
exit;
?>