<?php
session_start();

// Remove all session data
session_unset();

// Destroy the session
session_destroy();

// Redirect to the landing page
header("Location: ../index.php");
exit();
?>