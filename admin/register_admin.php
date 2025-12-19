<?php
session_start();
include '../users/db.php';

$message = '';
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $message = '<div class="alert alert-danger">Username already exists!</div>';
    } else {
        $stmt = $conn->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Admin registered successfully! <a href="../users/admin_login.php">Login here</a>.</div>';
        } else {
            $message = '<div class="alert alert-danger">Registration failed. Please try again.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/images/logo2.png">
    <style>
        body { background: #3b3d3f; }
        .register-container { max-width: 400px; margin: auto; margin-top: 5%; background-color: #fff; padding: 30px; border-radius: 8px; }
        .register-logo { display: flex; justify-content: center; margin-bottom: 15px; }
        .register-logo img { width: 50px; }
        .btn-coffee { background-color: #9b5c43; color: white; }
        .btn-coffee:hover { background-color: #7c4835; }
    </style>
</head>
<body>
    <div class="register-container shadow">
        <div class="register-logo">
            <img src="../assets/images/logo.png" alt="Coffee Logo">
        </div>
        <h3 class="text-center">COFFEE <br> LOUNGE</h3>
        <hr>
        <h4 class="text-center mb-4">Admin Registration</h4>
        <?php if ($message) echo $message; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" name="register" class="btn btn-coffee w-100">Register</button>
        </form>
        <div class="mt-3 text-center">
            <small>Already an admin? <a href="../users/admin_login.php">Login here!</a></small>
        </div>
    </div>
</body>
</html>
