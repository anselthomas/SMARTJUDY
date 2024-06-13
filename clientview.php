<?php
session_start();
include 'connect.php';

// Check if the user is logged in and user_name is set in the session
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in or user_name is not set in the session
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="clientview.css">
</head>

<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="cdashboard.php">Dashboard</a></li>
                <li><a href="">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="dashboard-container">
            <h1>Your Cases</h1>
            <table>
                <thead>
                    <tr>
                        <th>Cases</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display cases from the database for the logged-in user
                    $user_name = $_SESSION['username'];

                    $sql = "SELECT * FROM civil_case_details WHERE username = '$user_name'";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td><a href='case_details.php?type=civil&id=" . $row['id'] . "'>" . $row['case_number'] . "</a></td>";
                        echo "<td>Civil</td></tr>";
                    }

                    $sql = "SELECT * FROM criminal_case_details WHERE username = '$user_name'";
                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td><a href='case_details.php?type=criminal&id=" . $row['id'] . "'>" . $row['case_number'] . "</a></td>";
                        echo "<td>Criminal</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
