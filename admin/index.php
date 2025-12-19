<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: admin_dashboard.php');
    exit();
} else {
    header('Location: ../users/admin_login.php');
    exit();
}
