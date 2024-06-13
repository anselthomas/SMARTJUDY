<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $case_id = $_GET['id'];

    $user_name = $_SESSION['username'];
    $table_name = ($type === 'civil') ? 'civil_case_details' : 'criminal_case_details';

    // Fetch case details from the database for the logged-in user
    $sql = "SELECT * FROM $table_name WHERE id = '$case_id' AND username = '$user_name'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $case_details = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Case Details</title>
    <link rel="stylesheet" href="clientview.css">
</head>

<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="cdashboard.php">Dashboard</a></li>
                <li><a href="">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>

        <div class="container">
            <table>
                <tr>
                    <th>Case Number</th>
                    <td><?php echo htmlspecialchars($case_details['case_number']); ?></td>
                </tr>
                <?php if ($type === 'civil') { ?>
                <tr>
                    <th>Plaintiff Name</th>
                    <td><?php echo htmlspecialchars($case_details['plaintiff_name']); ?></td>
                </tr>
                <tr>
                    <th>Plaintiff Contact</th>
                    <td><?php echo htmlspecialchars($case_details['plaintiff_contact']); ?></td>
                </tr>
                <tr>
                    <th>Defendant Name</th>
                    <td><?php echo htmlspecialchars($case_details['defendant_name']); ?></td>
                </tr>
                <tr>
                    <th>Defendant Contact</th>
                    <td><?php echo htmlspecialchars($case_details['defendant_contact']); ?></td>
                </tr>
                <tr>
                    <th>Case Description</th>
                    <td><?php echo htmlspecialchars($case_details['case_description']); ?></td>
                </tr>
                <tr>
                    <th>Incident Date</th>
                    <td><?php echo htmlspecialchars($case_details['incident_date']); ?></td>
                </tr>
                <tr>
                    <th>Incident Location</th>
                    <td><?php echo htmlspecialchars($case_details['incident_location']); ?></td>
                </tr>
                <tr>
                    <th>Legal Documents</th>
                    <td><?php echo htmlspecialchars($case_details['legal_documents']); ?></td>
                </tr>
                <tr>
                    <th>Contracts</th>
                    <td><?php echo htmlspecialchars($case_details['contracts']); ?></td>
                </tr>
                <tr>
                    <th>Correspondence</th>
                    <td><?php echo htmlspecialchars($case_details['correspondence']); ?></td>
                </tr>
                <tr>
                    <th>Evidence</th>
                    <td><?php echo htmlspecialchars($case_details['evidence']); ?></td>
                </tr>
                <tr>
                    <th>Plaintiff ID</th>
                    <td><?php echo htmlspecialchars($case_details['plaintiff_id']); ?></td>
                </tr>
                <?php } else if ($type === 'criminal') { ?>
                <tr>
                    <th>Accused Name</th>
                    <td><?php echo htmlspecialchars($case_details['accused_name']); ?></td>
                </tr>
                <tr>
                    <th>Accused Address</th>
                    <td><?php echo htmlspecialchars($case_details['accused_address']); ?></td>
                </tr>
                <tr>
                    <th>Accused Phone</th>
                    <td><?php echo htmlspecialchars($case_details['accused_phone']); ?></td>
                </tr>
                <tr>
                    <th>Accused Date of Birth</th>
                    <td><?php echo htmlspecialchars($case_details['accused_dob']); ?></td>
                </tr>
                <tr>
                    <th>Case Specification</th>
                    <td><?php echo htmlspecialchars($case_details['case_spec']); ?></td>
                </tr>
                <tr>
                    <th>Criminal Charges</th>
                    <td><?php echo htmlspecialchars($case_details['criminal_charges']); ?></td>
                </tr>
                <tr>
                    <th>Incident Date</th>
                    <td><?php echo htmlspecialchars($case_details['incident_date']); ?></td>
                </tr>
                <tr>
                    <th>Incident Location</th>
                    <td><?php echo htmlspecialchars($case_details['incident_location']); ?></td>
                </tr>
                <tr>
                    <th>Arrest Report</th>
                    <td><?php echo htmlspecialchars($case_details['arrest_report']); ?></td>
                </tr>
                <tr>
                    <th>Charging Documents</th>
                    <td><?php echo htmlspecialchars($case_details['charging_documents']); ?></td>
                </tr>
                <tr>
                    <th>Court Summons</th>
                    <td><?php echo htmlspecialchars($case_details['court_summons']); ?></td>
                </tr>
                <tr>
                    <th>Court Orders</th>
                    <td><?php echo htmlspecialchars($case_details['court_orders']); ?></td>
                </tr>
                <tr>
                    <th>Witness Name</th>
                    <td><?php echo htmlspecialchars($case_details['witness_name']); ?></td>
                </tr>
                <tr>
                    <th>Witness Contact</th>
                    <td><?php echo htmlspecialchars($case_details['witness_contact']); ?></td>
                </tr>
                <tr>
                    <th>Witness Statement</th>
                    <td><?php echo htmlspecialchars($case_details['witness_statement']); ?></td>
                </tr>
                <tr>
                    <th>Physical Evidence</th>
                    <td><?php echo htmlspecialchars($case_details['physical_evidence']); ?></td>
                </tr>
                <tr>
                    <th>Digital Evidence</th>
                    <td><?php echo htmlspecialchars($case_details['digital_evidence']); ?></td>
                </tr>
                <tr>
                    <th>Accused ID</th>
                    <td><?php echo htmlspecialchars($case_details['accused_id']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    
</body>

</html>
<?php
    } else {
        // Case not found or not accessible
        echo "Case not accessible";
    }
} else {
    // Invalid URL parameters
    echo "Invalid URL parameters";
}
?>



