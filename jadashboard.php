<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legal1";
include("header.html");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
$result_cases = $conn->query($sql_cases);
?>

<!DOCTYPE html>
<html>
<head>
    <title>DASHBOARD</title>
    <link rel="stylesheet" href="sadashboard.css">
    <style>
        /* Your existing CSS styles go here */
    </style>
</head>
<body>
  
    <div class="dashboard-container">
        <div class="tabs">
            <button class="active" data-tab="case-files">Case Files</button>
            <button data-tab="View Existing Cases"><a href="view_by.php">View Existing Cases</a></button>
            <button data-tab="Edit/Update Cases"><a href="jupdate_case.php">Edit/Update Cases</a></button>
            <button data-tab="View and Update Documents"><a href="upload.php">View and Update Documents</a></button>
        </div>
        <div class="list" id="case-files-list">
            <table>
                <tr>
                    <th>Case ID</th>
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
                if ($result_cases->num_rows > 0) {
                    while ($row = $result_cases->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>| " . $row["id"] . " </td>";
                        echo "<td>| " . $row["case_year"] . " </td>";
                        echo "<td>| " . $row["case_type"] . " </td>";
                        echo "<td>| " . $row["case_number"] . " </td>";
                        echo "<td>| " . $row["petitioner_name"] . " </td>";
                        echo "<td>| " . $row["opposite_party"] . " </td>";
                        echo "<td>| " . $row["appointed_lawyer"] . " </td>";
                        echo "<td>| " . $row["responsible_officer"] . " </td>";
                        echo "<td>| " . $row["case_status"] . " </td>";
                        echo "<td>| " . $row["status_date"] . " </td>";
                        echo "<td>| " . $row["description"] . " </td>";
                        echo "<td>| " . $row["remarks"] . " </td>";
                        echo "<td>| " . $row["document_path"] . " </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='13'>No cases found.</td></tr>";
                }
                ?>
     </table>
        </div>
        <div class="add-files-button">
            <ul>
                <li>
                    <button><a href="index.php"><span>+</span> Back Home</a></button>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
