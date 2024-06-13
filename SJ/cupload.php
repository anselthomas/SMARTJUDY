<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

include("connect.php");

// Function to create a zip archive of the files in the given directory
function createZipArchive($dir, $zipFile) {
    $zip = new ZipArchive();
    if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
        $files = glob($dir . "*");
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["case_number"]) && isset($_POST["case_type"])) {
    // Fetch case details from the form submission
    $case_number = $_POST["case_number"];
    $case_type = $_POST["case_type"];

    // Prepare the directory path based on case details
    $upload_dir =  $case_type . '_case_files/' . $case_number . '/';

    // Create the directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Upload files to the directory
    if (isset($_FILES["file_upload"])) {
        $file_count = count($_FILES["file_upload"]["name"]);
        for ($i = 0; $i < $file_count; $i++) {
            $tmp_name = $_FILES["file_upload"]["tmp_name"][$i];
            $file_name = $_FILES["file_upload"]["name"][$i];
            move_uploaded_file($tmp_name, $upload_dir . $file_name);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Upload and Download</title>
    <style type="text/css">
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
            background-color: transparent; /* Make the container transparent */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            padding: 20px;
            color: rgb(255, 255, 255); /* Set the text color to black */
        }

        /* Style the form inputs */
        .dashboard-container form label {
            display: block;
            margin-bottom: 10px;
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
        }

        /* Style the form submit buttons */
        .dashboard-container form input[type="submit"] {
            background-color: #3399cc;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        .dashboard-container form input[type="submit"]:hover {
            background-color: #008cba;
        }

        .btn-download {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #3399cc;
            color: #ffffff;
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
                <li class="active"><a href="#">Dashboard</a></li>
                <li><a href="Profile.html">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="dashboard-container">
            <h2>Client Upload and Download</h2>
            <br><br><br>
            <!-- File upload form -->
            <form action="cupload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="case_number">Case Number:</label>
                    <!-- The case number input is pre-filled with the value from the clientview.php -->
                    <input type="text" name="case_number" id="case_number" value="<?php if(isset($_GET['case_number'])) { echo $_GET['case_number']; } ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="case_type">Case Type:</label>
                    <select name="case_type" id="case_type" required>
                        <option value="">Select Case Type</option>
                        <option value="criminal">Criminal</option>
                        <option value="civil">Civil</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="file_upload">Select File:</label>
                    <input type="file" name="file_upload[]" id="file_upload" multiple required>
                </div>
                <input type="submit" name="submit" value="Upload" class="btn-submit">
            </form>

            <br>

            <!-- File download button -->
            <?php
            if (isset($_POST["case_number"]) && isset($_POST["case_type"])) {
                // Fetch case details from the form submission
                $case_number = $_POST["case_number"];
                $case_type = $_POST["case_type"];

                // Prepare the directory path based on case details
                $upload_dir =  $case_type . '_case_files/' . $case_number . '/';

                // Get the list of files in the directory
                $file_list = glob($upload_dir . "*");

                if (count($file_list) > 0) {
                    echo "<h3>Uploaded Documents:</h3>";
                    // List all the files as links for download
                    foreach ($file_list as $file_path) {
                        $file_name = basename($file_path);
                        echo "<a href=\"$file_path\" target=\"_blank\">$file_name</a><br>";
                    }

                    // Create a zip archive of the files and provide a download link
                    $zipFileName = "$case_type-$case_number.zip";
                    createZipArchive($upload_dir, $zipFileName);
                    echo "<a href=\"$zipFileName\" download class=\"btn-download\">Download All as ZIP</a>";
                } else {
                    echo "<p>No documents uploaded for this case.</p>";
                }
            } else {
                echo "<p>Case details not provided.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
