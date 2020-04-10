<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
unset($_SESSION["loggedin"]);
// Destroy the session.
session_destroy();

// Get url variables to return to same page if logout not from homepage
$redirectlocation = $_GET["redir"];
if($redirectlocation == 'service') {
$service = $_GET["service"];
header("location: ../../../services?service=".$service);
exit;
} elseif(!isset($redirectlocation)) {
header("location: ../../../?logout=true");
exit; };
?>