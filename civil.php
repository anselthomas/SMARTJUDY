<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['username'])) {
        $plaintiff_name = $_POST['plaintiff_name'];
        $plaintiff_contact = $_POST['plaintiff_contact'];
        $defendant_name = $_POST['defendant_name'];
        $defendant_contact = $_POST['defendant_contact'];
        $case_spec = $_POST['options'];
        $case_description = $_POST['case_description'];
        $incident_date = $_POST['incident_date'];
        $incident_location = $_POST['incident_location'];
        $legal_documents = $_FILES['legal_documents']['name'];
        $contracts = $_FILES['contracts']['name'];
        $correspondence = $_FILES['correspondence']['name'];
        $evidence = $_FILES['evidence']['name'];
        $plaintiff_id = $_FILES['plaintiff_id']['name'];
        $user_name = $_SESSION['username'];

        $sql = "INSERT INTO civil_case_details (username, plaintiff_name, plaintiff_contact, defendant_name, defendant_contact, case_spec, case_description, incident_date, incident_location, legal_documents, contracts, correspondence, evidence, plaintiff_id)
                VALUES ('$user_name', '$plaintiff_name', '$plaintiff_contact', '$defendant_name', '$defendant_contact', '$case_spec', '$case_description', '$incident_date', '$incident_location', '$legal_documents', '$contracts', '$correspondence', '$evidence', '$plaintiff_id')";
        if (mysqli_query($conn, $sql)) {
            $result = mysqli_query($conn, "SELECT case_number FROM civil_case_details WHERE username = '$user_name' ORDER BY case_number DESC LIMIT 1");
            $row = mysqli_fetch_assoc($result);
            $case_number = $row['case_number'];

            echo "Data inserted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
            exit();
        }

        $parent_directory = "civil_case_files/";
        if (!is_dir($parent_directory)) {
            mkdir($parent_directory);
        }

        $case_folder = $parent_directory . $case_number . "/";
        if (!is_dir($case_folder)) {
            mkdir($case_folder);
        }

        $target_dir = $case_folder;
        move_uploaded_file($_FILES['legal_documents']['tmp_name'], $target_dir . $legal_documents);
        move_uploaded_file($_FILES['contracts']['tmp_name'], $target_dir . $contracts);
        move_uploaded_file($_FILES['correspondence']['tmp_name'], $target_dir . $correspondence);
        move_uploaded_file($_FILES['evidence']['tmp_name'], $target_dir . $evidence);
        move_uploaded_file($_FILES['plaintiff_id']['tmp_name'], $target_dir . $plaintiff_id);
    } else {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Civilcasedetails</title>
    <link rel="stylesheet" href="civil.css">
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
        <div class="container">
            <form action="civil.php" method="POST" enctype="multipart/form-data">
                <h3>Personal Information</h3>
                <h4>Plaintiff Information:</h4>
                <label for="plaintiff_name">Plaintiff's Name:</label>
                <input type="text" id="plaintiff_name" name="plaintiff_name" required>
                <label for="plaintiff_contact">Plaintiff's Contact:</label>
                <input type="tel" id="plaintiff_contact" name="plaintiff_contact" pattern="[0-9]{10}" required>
                <h4>Defendant Information:</h4>
                <label for="defendant_name">Defendant's Name:</label>
                <input type="text" id="defendant_name" name="defendant_name" required>
                <label for="defendant_contact">Defendant's Mobile Number:</label>
                <input type="tel" id="defendant_contact" name="defendant_contact" pattern="[0-9]{10}" required>

                <h3>Case Information</h3>
                <label for="options" class="cc">Case Type:</label>
                <select id="options" name="options" onchange="showDescription()">
                    <option value="">-- Select --</option>
                    <option value="Damage to property">Damage to property</option>
                    <option value="Probate issues">Probate issues</option>
                    <option value="Family issues">Family issues</option>
                    <!-- Other options... -->
                </select>
                <label for="case_description">Description of the Civil Case:</label>
                <textarea id="case_description" name="case_description" required></textarea>
                <label for="incident_date">Date of Incident:</label>
                <input type="date" id="incident_date" name="incident_date">
                <label for="incident_location">Location of Incident:</label>
                <input type="text" id="incident_location" name="incident_location">

                <h3>Supporting Documents:</h3>
                <label for="legal_documents">Legal Documents (Complaint, Petition):</label>
                <input type="file" id="legal_documents" name="legal_documents" accept=".pdf" required>
                <!-- Other file inputs... -->

                <h3>Identification Documents:</h3>
                <label for="plaintiff_id">Plaintiff's ID (e.g., Driver's License, Passport):</label>
                <input type="file" id="plaintiff_id" name="plaintiff_id" accept=".pdf, .jpg, .jpeg, .png" required>

                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</body>

</html>

