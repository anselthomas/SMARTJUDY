<?php 
session_start();
include 'connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>CDASHBOARD</title>
    <link rel="stylesheet" href="cdashboard.css">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="#">Dashboard</a></li>
                <li><a href="">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="dashboard-container">
            <div class="center-buttons">
                <a href="casetype.php"><button>Post Your Case</button></a>
                <a href="clientview.php"><button>Case Records</button></a>
            </div>
        </div>
    </div>
</body>
</html>




