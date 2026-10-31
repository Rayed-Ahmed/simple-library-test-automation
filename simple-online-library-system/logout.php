<?php
// logout.php
require_once __DIR__ . '/config/database.php';
$_SESSION = array();
session_destroy();
header("location: " . BASE_PATH . "login.php");
exit;
?>