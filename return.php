<?php
session_start();
include("connect.php");

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Fetch the user's role from the 'users' table
    $username = $_SESSION['username'];
    $sql_user = "SELECT role FROM users WHERE username = '$username' LIMIT 1";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        $row_user = $result_user->fetch_assoc();
        $role = $row_user['role'];

        // Redirect based on user role
        if ($role === 'junior advocate') {
            header("Location: jadashboard.php");
            exit();
        } elseif ($role === 'senior advocate') {
            header("Location: sadashboard.php");
            exit();
        }
    }
}

// Default redirect if user is not logged in or role is not recognized
header("Location: login.php");
exit();
?>
