<?php 
session_start();
include 'connect.php';
?>
<html>
<head>
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="casetype.css">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="cdashboard.php">Dashboard</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="dashboard-container">
            <div class="text">
                <p>Case Type
                </p>
            </div>
            <div class="center-buttons">
                <a href="civil.php"><button>Civil Case</button></a>
                <a href="criminal.php"><button>Criminal Case</button></a>
            </div>
        </div>
    </div>
</body>
</html>