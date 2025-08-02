<?php
session_start();
session_destroy(); // Destroy all sessions
header("Location: ../frontend/login.php"); // Redirect to login page
exit();
?>
