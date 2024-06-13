<?php
session_start();

include("connect.php");

// Fetch the full name of the user from the 'users' table
$full_name = "";
$username = $_SESSION['username'];
$sql_user = "SELECT full_name FROM users WHERE username = '$username' LIMIT 1";
$result_user = $conn->query($sql_user);
if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $full_name = $row_user['full_name'];
}

// Fetch the cases appointed to the user based on their full name
$sql_cases = "SELECT * FROM cases WHERE appointed_lawyer = '$full_name'";
$result = $conn->query($sql_cases);
?>

<!DOCTYPE html>
<html>
<head>
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="sadashboard.css">
  
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
            <div class="tabs">
            <div class="tabs">
                <a class="active" href="#case-files">Case Files</a>
                <a href="jupdate_case.php">Edit/Update Cases</a>
                <a href="download_all.php">View Documents</a>
                <a href="upload.php">Upload Documents</a>
                <a href="view_by.php">View Existing Cases</a>  
            </div>
            </div>
            <div class="list" id="case-files-list">
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Petitioner Name</th>
                        <th>Opposite Party</th>
                        <th>Appointed Lawyer</th>
                        <th>Responsible Officer</th>
                        <th>Status</th>
                        <th>Status Date</th>
                        <th>Description</th>
                        <th>Remarks</th>
                        <th>Document Path</th>
                    </tr>
                    <?php
                    // Display cases data in the table
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td> " . $row["case_year"] . " </td>";
                            echo "<td> " . $row["case_type"] . " </td>";
                            echo "<td> " . $row["case_number"] . " </td>";
                            echo "<td> " . $row["petitioner_name"] . " </td>";
                            echo "<td> " . $row["opposite_party"] . " </td>";
                            echo "<td> " . $row["appointed_lawyer"] . " </td>";
                            echo "<td> " . $row["responsible_officer"] . " </td>";
                            echo "<td> " . $row["case_status"] . " </td>";
                            echo "<td> " . $row["status_date"] . " </td>";
                            echo "<td> " . $row["description"] . " </td>";
                            echo "<td> " . $row["remarks"] . " </td>";
                            echo "<td> " . $row["document_path"] . " </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='13'>No cases found.</td></tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
