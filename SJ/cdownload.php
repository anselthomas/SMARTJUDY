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

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["case_number"]) && isset($_GET["case_type"])) {
    $case_number = $_GET["case_number"];
    $case_type = $_GET["case_type"];

    // Prepare the directory path based on case details
    $upload_dir = $case_type . '_case_files/' . $case_number . '/';

    // Get the list of files in the directory
    $file_list = glob($upload_dir . "*");

    if (count($file_list) > 0) {
        // Show links to each file for individual download
        echo "<h3>Uploaded Documents:</h3>";
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
    echo "<p>Invalid request.</p>";
}

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
?>
