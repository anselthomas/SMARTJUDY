<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the user is logged in and user_name is set in the session
    if (isset($_SESSION['username'])) {
        // Personal Information
        $accused_name = $_POST['accused_name'];
        $accused_address = $_POST['accused_address'];
        $accused_phone = $_POST['accused_phone'];
        $accused_dob = $_POST['accused_dob'];
        $suspect_name=$_POST['suspect_name'];

        // Case Information
        $case_spec = $_POST['options'];
        $criminal_charges = $_POST['criminal_charges'];
        $incident_date = $_POST['incident_date'];
        $incident_location = $_POST['incident_location'];

        // File Uploads (Arrest and Charging Documents)
        $arrest_report = $_FILES['arrest_report']['name'];
        $charging_documents = $_FILES['charging_documents']['name'];

        // File Uploads (Court Documents)
        $court_summons = $_FILES['court_summons']['name'];
        $court_orders = $_FILES['court_orders']['name'];

        // Witness Information
        $witness_name = $_POST['witness_name'];
        $witness_contact = $_POST['witness_contact'];
        $witness_statement = $_POST['witness_statement'];

        // File Uploads (Evidence)
        $physical_evidence = $_FILES['physical_evidence']['name'];
        $digital_evidence = $_FILES['digital_evidence']['name'];

        // File Uploads (Identification Documents)
        $accused_id = $_FILES['accused_id']['name'];

        // Get the user_name from the session
        $user_name = $_SESSION['username'];


        // Insert data into the database
        $sql = "INSERT INTO criminal_case_details (username, accused_name, accused_address, accused_phone, accused_dob,suspect_name ,case_spec, criminal_charges, incident_date, incident_location, arrest_report, charging_documents, court_summons, court_orders, witness_name, witness_contact, witness_statement, physical_evidence, digital_evidence, accused_id)
            VALUES ('$user_name', '$accused_name', '$accused_address', '$accused_phone', '$accused_dob','$suspect_name', '$case_spec', '$criminal_charges', '$incident_date', '$incident_location', '$arrest_report', '$charging_documents', '$court_summons', '$court_orders', '$witness_name', '$witness_contact', '$witness_statement', '$physical_evidence', '$digital_evidence', '$accused_id')";

        if (mysqli_query($conn, $sql)) {
            $result = mysqli_query($conn, "SELECT case_number FROM criminal_case_details WHERE username = '$user_name' ORDER BY case_number DESC LIMIT 1");
            $row = mysqli_fetch_assoc($result);
            $case_number = $row['case_number'];

            echo "Data inserted successfully!";
            header("Location: cdashboard.php");
            
        } else {
            echo "Error: " . mysqli_error($conn);
            exit();
        }
        // Create the parent directory for case files if it doesn't exist
        $parent_directory = "criminal_case_files/";
        if (!is_dir($parent_directory)) {
            mkdir($parent_directory);
        }

        // Create a separate folder for each case using the format "user_name_case_number"
        $case_folder = $parent_directory . $case_number . "/";
        if (!is_dir($case_folder)) {
            mkdir($case_folder);
             // Move uploaded files to the desired location (inside the case folder)
        $target_dir = $case_folder; // Use the case folder as the target directory
        move_uploaded_file($_FILES['arrest_report']['tmp_name'], $target_dir . $arrest_report);
        move_uploaded_file($_FILES['charging_documents']['tmp_name'], $target_dir . $charging_documents);
        move_uploaded_file($_FILES['court_summons']['tmp_name'], $target_dir . $court_summons);
        move_uploaded_file($_FILES['court_orders']['tmp_name'], $target_dir . $court_orders);
        move_uploaded_file($_FILES['physical_evidence']['tmp_name'], $target_dir . $physical_evidence);
        move_uploaded_file($_FILES['digital_evidence']['tmp_name'], $target_dir . $digital_evidence);
        move_uploaded_file($_FILES['accused_id']['tmp_name'], $target_dir . $accused_id);


        }
    } else {
        // Redirect to login page if user is not logged in or user_name is not set in the session
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Criminalcasedetails</title>

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
            <form action="criminal.php" method="POST" enctype="multipart/form-data">
                <h3>Personal Information</h3>
                <label for="accused_name">Full Name of the Accused:</label>
                <input type="text" id="accused_name" name="accused_name" required>
                <label for="accused_address">Address of the Accused:</label>
                <input type="text" id="accused_address" name="accused_address" required>
                <label for="accused_phone">Phone Number of the Accused:</label>
                <input type="tel" id="accused_phone" name="accused_phone" pattern="[0-9]{10}" required>
                <label for="accused_dob">Date of Birth of the Accused:</label>
                <input type="date" id="accused_dob" name="accused_dob" required>
                <label for="suspect_name">Full name of Suspect:</label>
                <input type="text" id="suspect_name" name="suspect_name" required>

                <h3>Case Information</h3>
                <label for="options" class="cc">Choose Type:</label>
                <select id="options" name='options' onchange="showDescription()">
                    <option value="">-- Select --</option>
                    <option value="Theft">Theft</option>
                    <option value="Physical Assault">Physical Assault</option>
                    <option value="Murder">Murder</option>
                    <option value="Others">Others</option>
                </select>
                <label for="criminal_charges">Description of Criminal Charges:</label>
                <textarea id="criminal_charges" name="criminal_charges" required></textarea>
                <label for="incident_date">Date of Incident:</label>
                <input type="date" id="incident_date" name="incident_date">
                <label for="incident_location">Location of Incident:</label>
                <input type="text" id="incident_location" name="incident_location">

                <h3>Arrest and Charging Documents:</h3>
                <label for="arrest_report">Arrest Report or Incident Report:</label>
                <input type="file" id="arrest_report" name="arrest_report" accept=".pdf" required>
                <label for="charging_documents">Charging Documents (Complaint, Indictment, etc.):</label>
                <input type="file" id="charging_documents" name="charging_documents" accept=".pdf" required>

                <h3>Court Documents:</h3>
                <label for="court_summons">Court Summons or Warrants:</label>
                <input type="file" id="court_summons" name="court_summons" accept=".pdf" required>
                <label for="court_orders">Court Orders or Notices:</label>
                <input type="file" id="court_orders" name="court_orders" accept=".pdf" required>

                <h3>Witness Information:</h3>
                <label for="witness_name">Witness Name:</label>
                <input type="text" id="witness_name" name="witness_name">
                <label for="witness_contact">Witness Contact Information:</label>
                <input type="text" id="witness_contact" name="witness_contact">
                <label for="witness_statement">Witness Statement:</label>
                <textarea id="witness_statement" name="witness_statement"></textarea>

                <h3>Evidence:</h3>
                <label for="physical_evidence">Physical Evidence (e.g., photographs, video recordings, objects):</label>
                <input type="file" id="physical_evidence" name="physical_evidence" accept=".pdf, .jpg, .jpeg, .png" required>
                <label for="digital_evidence">Digital Evidence (e.g., electronic communications, documents):</label>
                <input type="file" id="digital_evidence" name="digital_evidence" accept=".pdf, .jpg, .jpeg, .png" required>

                <h3>Identification Documents:</h3>
                <label for="accused_id">Accused's ID (e.g., Driver's License, Passport):</label>
                <input type="file" id="accused_id" name="accused_id" accept=".pdf, .jpg, .jpeg, .png" required>

                <input type="submit" value="Submit">
            </form>
        </div>
</body>

</html>
</html>