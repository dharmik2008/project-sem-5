<?php
session_start();

// Only unset admin-specific session and then destroy session
if (isset($_SESSION['admin'])) {
	unset($_SESSION['admin']);
}

// Optionally clear all session data
session_unset();
session_destroy();

// Redirect to admin login page
header('Location: ../users/admin_login.php');
exit();
