<!DOCTYPE html>
<html>
<head>
    <title>View Case Details</title>
    <link rel="stylesheet" href="clientview.css">
</head>
<body>
    <h1>Case Details</h1>
    <?php
    // Include the database connection
    include 'connect.php';

    // Function to retrieve case details from the database
    function getCaseDetails($conn, $case_type, $case_number)
    {
        if ($case_type === 'civil') {
            $table_name = 'civil_case_details';
        } elseif ($case_type === 'criminal') {
            $table_name = 'criminal_case_details';
        } else {
            // Invalid case type
            return false;
        }

        $sql = "SELECT * FROM $table_name WHERE case_number = '$case_number'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return false;
        }
    }

    // Check if the case_type and case_number are set in the query string
    if (isset($_GET['case_type']) && isset($_GET['case_number'])) {
        $case_type = $_GET['case_type'];
        $case_number = $_GET['case_number'];

        // Get the case details from the database
        $case_details = getCaseDetails($conn, $case_type, $case_number);

        if ($case_details) {
            // Display case details
            echo "<table>";
            echo "<tr><th>Case Number</th><td>" . htmlspecialchars($case_details['case_number']) . "</td></tr>";
            if ($case_type === 'civil') {
                echo "<tr><th>Plaintiff Name</th><td>" . htmlspecialchars($case_details['plaintiff_name']) . "</td></tr>";
                echo "<tr><th>Plaintiff Contact</th><td>" . htmlspecialchars($case_details['plaintiff_contact']) . "</td></tr>";
                echo "<tr><th>Defendant Name</th><td>" . htmlspecialchars($case_details['defendant_name']) . "</td></tr>";
                echo "<tr><th>Defendant Contact</th><td>" . htmlspecialchars($case_details['defendant_contact']) . "</td></tr>";
                echo "<tr><th>Case Description</th><td>" . htmlspecialchars($case_details['case_description']) . "</td></tr>";
                echo "<tr><th>Incident Date</th><td>" . htmlspecialchars($case_details['incident_date']) . "</td></tr>";
                echo "<tr><th>Incident Location</th><td>" . htmlspecialchars($case_details['incident_location']) . "</td></tr>";
                echo "<tr><th>Legal Documents</th><td>" . htmlspecialchars($case_details['legal_documents']) . "</td></tr>";
                echo "<tr><th>Contracts</th><td>" . htmlspecialchars($case_details['contracts']) . "</td></tr>";
                echo "<tr><th>Correspondence</th><td>" . htmlspecialchars($case_details['correspondence']) . "</td></tr>";
                echo "<tr><th>Evidence</th><td>" . htmlspecialchars($case_details['evidence']) . "</td></tr>";
                echo "<tr><th>Plaintiff ID</th><td>" . htmlspecialchars($case_details['plaintiff_id']) . "</td></tr>";
            } else if ($case_type === 'criminal') {
                echo "<tr><th>Accused Name</th><td>" . htmlspecialchars($case_details['accused_name']) . "</td></tr>";
                echo "<tr><th>Accused Address</th><td>" . htmlspecialchars($case_details['accused_address']) . "</td></tr>";
                echo "<tr><th>Accused Phone</th><td>" . htmlspecialchars($case_details['accused_phone']) . "</td></tr>";
                echo "<tr><th>Accused Date of Birth</th><td>" . htmlspecialchars($case_details['accused_dob']) . "</td></tr>";
                echo "<tr><th>Case Specification</th><td>" . htmlspecialchars($case_details['case_spec']) . "</td></tr>";
                echo "<tr><th>Criminal Charges</th><td>" . htmlspecialchars($case_details['criminal_charges']) . "</td></tr>";
                echo "<tr><th>Accused ID</th><td>" . htmlspecialchars($case_details['accused_id']) . "</td></tr>";
                echo "<tr><th>Arrest Report</th><td>" . htmlspecialchars($case_details['arrest_report']) . "</td></tr>";
                echo "<tr><th>Charging Documents</th><td>" . htmlspecialchars($case_details['charging_documents']) . "</td></tr>";
                echo "<tr><th>Court Summons</th><td>" . htmlspecialchars($case_details['court_summons']) . "</td></tr>";
                echo "<tr><th>Court Orders</th><td>" . htmlspecialchars($case_details['court_orders']) . "</td></tr>";
                echo "<tr><th>Witness Name</th><td>" . htmlspecialchars($case_details['witness_name']) . "</td></tr>";
                echo "<tr><th>Witness Contact</th><td>" . htmlspecialchars($case_details['witness_contact']) . "</td></tr>";
                echo "<tr><th>Witness Statement</th><td>" . htmlspecialchars($case_details['witness_statement']) . "</td></tr>";
                echo "<tr><th>Physical Evidence</th><td>" . htmlspecialchars($case_details['physical_evidence']) . "</td></tr>";
                echo "<tr><th>Digital Evidence</th><td>" . htmlspecialchars($case_details['digital_evidence']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Case not found or not accessible.</p>";
        }
    } else {
        // case_type or case_number not set in the query string
        echo "<p>Invalid case details.</p>";
    }
    ?>
    <p><a href="advocate_interface.php">Back to Case List</a></p>
</body>
</html>
