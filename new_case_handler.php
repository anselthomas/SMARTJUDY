<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "legal1";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare data to be inserted
    $case_type = $_POST['case_type'];
    $case_year = $_POST['case_year'];

    // Fetch the id from the cases table based on case_type and case_year
    $query = "SELECT id FROM cases WHERE case_type = '$case_type' AND case_year = '$case_year' ORDER BY id DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'] + 1; // Increment the fetched id by 1 for the new case
    } else {
        // If no existing cases with the specified case_type and case_year, start with id = 1
        $id = 1;
    }

    // Form the case_number as case_type/id/case_year
    $case_num = $case_type . '/' . $id . '/' . $case_year;

    $petitioner_name = $_POST['petitioner_name'];
    $versus = $_POST['versus'];
    $advocate_name = $_POST['advocate_name'];
    $officer_name = $_POST['officer_name'];
    $description = $_POST['description'];
    $remarks = $_POST['remarks'];

    // Sanitize user input to prevent SQL injection
    $case_num = mysqli_real_escape_string($conn, $case_num);
    $petitioner_name = mysqli_real_escape_string($conn, $petitioner_name);
    $versus = mysqli_real_escape_string($conn, $versus);
    $advocate_name = mysqli_real_escape_string($conn, $advocate_name);
    $officer_name = mysqli_real_escape_string($conn, $officer_name);
    $description = mysqli_real_escape_string($conn, $description);
    $remarks = mysqli_real_escape_string($conn, $remarks);

    // Insert the case details into the database
    $query = "INSERT INTO cases (case_year, case_type, case_number, petitioner_name, opposite_party, appointed_lawyer, responsible_officer, case_status, description, remarks, document_path)
              VALUES ('$case_year', '$case_type', '$case_num', '$petitioner_name', '$versus', '$advocate_name', '$officer_name', 'pending', '$description', '$remarks', '')";

    if ($conn->query($query) === true) {
        $message = "Data Successfully Inserted";
    } else {
        $message = "There is an error with the data (Data may already be present). Not Inserted.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Legal File System</title>
    <script src="hindi.js"></script>
    <link rel="stylesheet" type="text/css" href="form_style.css" />
    <style>
        table {
            border-collapse: collapse;
        }

        td {
            padding-top: .2em;
            padding-bottom: .2em;
        }
    </style>
</head>
<body style="background-color:#ffff99;">
    <center>
        <div style="border:5px solid black; width:950px; height:600px;">
            <div Style="font-size:45px; margin-top:50px;"><b>SMART JUDY</b></div>
            <div Style="font-size:35px; margin-top:10px; color:red; margin-bottom:10px;"><b>File Tracking System</b></div>
            <img src="logo.png" alt="logo" style="width:500px; height:130px; margin-bottom:15px;"><br><br>
            <div style="color:RED;font-size:25px ">
                <center>
                    <input type="button" onclick="window.location='return.php'" value="Back"><br><br>
                    <b><?php echo $message; ?></b>
                </center>
            </div>
        </div>
    </center>
</body>
</html>
