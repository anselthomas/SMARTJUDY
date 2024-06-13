<?php
session_start();
include 'connect.php';

// Fetch user's role from the database
$username = $_SESSION['username'];
$sql_user = "SELECT role, full_name FROM users WHERE username = '$username'";
$result_user = $conn->query($sql_user);
$user_data = $result_user->fetch_assoc();
$user_role = $user_data['role'];
$user_name = $user_data['full_name'];

// Fetch cases based on user's role
if ($user_role === 'junior advocate') {
    $sql = "SELECT * FROM cases WHERE appointed_lawyer = '$user_name'";
} else {
    $sql = "SELECT * FROM cases";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Communication</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .banner {
            width: 100%;
            min-height: 100vh;
            background-image: linear-gradient(rgba(133, 49, 169, 0.75), rgba(6, 65, 148, 0.75)), url(background.jpg);
            background-size: cover;
            background-position: center;
        }

        .navbar {
            width: 85%;
            margin: auto;
            padding: 35px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar ul {
            list-style: none;
            display: flex;
        }

        .navbar ul li {
            margin: 0 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            text-transform: uppercase;
        }

        .logo {
            width: 300px;
            cursor: pointer;
        }

        /* Styles for the dashboard container and tabs */
        .dashboard-container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            padding: 20px;
            color: black;
            text-align: center;
        }

        /* Style the form inputs */
        .dashboard-container form label {
            display: block;
            margin-bottom: 10px;
            color: black;
        }

        .dashboard-container form input,
        .dashboard-container form textarea,
        .dashboard-container form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            color: black;
            text-align: center;
        }

        /* Style the form submit buttons */
        .dashboard-container form input[type="submit"] {
            background-color: #3399cc;
            color: black;
            text-align: center;
            border: none;
            cursor: pointer;
        }

        .dashboard-container form input[type="submit"]:hover {
            background-color: #008cba;
            color: black;
        }

        .btn-download {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3399cc;
            color: black;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-download:hover {
            background-color: #008cba;
        }

    </style>
</head>
<body>
<div class="banner">
    <div class="navbar">
        <img src="logo.png" class="logo">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li class="active"><a href="sadashboard.php">Dashboard</a></li>
            <li><a href="Profile.html">Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </div>
    <div class="dashboard-container">
        <h2>Client Communication - Cases</h2>
        <table>
            <tr>
                <th>Case Number</th>
            </tr>
            <?php
            // Display cases data in the table
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["case_number"] . "</td>";
                    echo "<td><a href='case_log_interface.php?case_number=" . $row["case_number"] . "'>View Case Log</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No cases found.</td></tr>";
            }
            ?>
        </table>
        <a class="back-button" href="sadashboard.php">Back</a>
    </div>
</div>
</body>
</html>
