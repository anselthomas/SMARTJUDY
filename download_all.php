<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legal1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["case_number"]) && isset($_POST["case_type"])) {
    $case_number = $_POST["case_number"];
    $case_type = $_POST["case_type"];

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

    // Prepare the directory path based on case details
    $upload_dir = $case_type . '_case_files/' . $case_number . '/';

    // Get the list of files in the directory
    $file_list = glob($upload_dir . "*");

    if (count($file_list) > 0) {
        echo "<h3>Uploaded Documents:</h3>";
        // List all the files as links for download
        foreach ($file_list as $file_path) {
            $file_name = basename($file_path);
            echo "<a href=\"$file_path\" download class=\"btn-downld-indiv\">Download $file_name</a><br>";
        }

        // Create a zip archive of the files and provide a download link
        $zipFileName = "$case_type-$case_number.zip";
        createZipArchive($upload_dir, $zipFileName);
        echo "<a href=\"$zipFileName\" download class=\"btn-downld\">Download All as ZIP</a>";
    } else {
        echo "<p>No documents uploaded for this case.</p>";
    }
} else {
    echo "<p>Case details not provided.</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download All Documents</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Download All Documents</h2>
        <!-- File download form -->
        <form action="download_all.php" method="post">
            <div class="form-group">
                <label for="case_number">Case Number:</label>
                <input type="text" name="case_number" id="case_number" required>
            </div>
            <div class="form-group">
                <label for="case_type">Case Type:</label>
                <select name="case_type" id="case_type" required>
                    <option value="">-- Select Case Type --</option>
                    <option value="Civil">Civil</option>
                    <option value="Criminal">Criminal</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Download" class="btn-downld">
            </div>
        </form>

        <a href="index.php" class="btn-back">Back to Home</a>
    </div>
</body>
</html>
