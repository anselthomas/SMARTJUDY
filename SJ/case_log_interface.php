

<?php
session_start();
include 'connect.php';

// Check if the user is logged in and user_name is set in the session
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in or user_name is not set in the session
    header("Location: login.php");
    exit();
}

// Fetch the user's role from the database
$user_name = $_SESSION['username'];
$fetch_role_query = "SELECT role FROM users WHERE username = '$user_name'";
$fetch_role_result = mysqli_query($conn, $fetch_role_query);

if (mysqli_num_rows($fetch_role_result) > 0) {
    $user_data = mysqli_fetch_assoc($fetch_role_result);
    $user_role = $user_data['role'];

    // Check if the user role is 'client'
    if ($user_role === 'client') {
        // If the user is a client, disable the form and other functionality to add logs
        $readonly = "readonly";
    } else {
        // If the user is an advocate, they can add logs
        $readonly = "";
    }
} else {
    // Unable to fetch user's role, redirect to login page or handle the error as needed
    header("Location: login.php");
    exit();
}

// Check if the case number is provided via GET
if (isset($_GET['case_number'])) {
    $case_number = $_GET['case_number'];

    // Fetch the case details using the provided case number
    $fetch_query = "SELECT * FROM cases WHERE case_number = '$case_number'";
    $fetch_result = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);

        // Assign the fetched data to variables
        $status_date=$row['status_date'];
        $petitioner_name = $row['petitioner_name'];
        $opposite_party = $row['opposite_party'];
        $case_status = $row['case_status'];
        $appointed_lawyer=$row['appointed_lawyer'];
    } else {
        // Case number not found in the database
        $error_message = "Case number not found in the database.";
    }

    // Function to get case logs from JSON file
    function getCaseLogs($case_number)
    {
        $json_file_path = "case_logs.json";
        if (file_exists($json_file_path)) {
            $json_data = file_get_contents($json_file_path);
            $case_logs = json_decode($json_data, true);
            if (isset($case_logs[$case_number])) {
                return $case_logs[$case_number];
            }
        }
        return [];
    }

    // Function to add a new case log to JSON file
    function addCaseLog($case_number, $log_text)
    {
        $json_file_path = "case_logs.json";
        $new_log = array(
            "log_date" => date("Y-m-d H:i:s"), // Use current date and time as log date
            "log_text" => $log_text
        );

        // Check if the JSON file exists
        if (file_exists($json_file_path)) {
            // Get existing case logs from JSON file
            $json_data = file_get_contents($json_file_path);
            $case_logs = json_decode($json_data, true);
        } else {
            // Create a new array if JSON file doesn't exist
            $case_logs = array();
        }

        // Add the new case log to the case_logs array
        $case_logs[$case_number][] = $new_log;

        // Convert the case_logs array to JSON format
        $updated_json_data = json_encode($case_logs, JSON_PRETTY_PRINT);

        // Write the updated JSON data back to the file
        file_put_contents($json_file_path, $updated_json_data);

        // Redirect back to the case log interface with the updated logs
        header("Location: case_log_interface.php?case_number=$case_number");
        exit();
    }

    // Fetch existing case logs
    $case_logs = getCaseLogs($case_number);

    // Check if the form is submitted to add a new case log
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $log_text = $_POST['log_text'];
        // Call the function to add the new case log
        addCaseLog($case_number, $log_text);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Case Log Interface</title>
    <style>
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
                body {
            font-family:  sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
        }

        h2 {
            color: white ;
            margin-bottom: 10px;
        }

        p {
            margin: 5px 0;
        }

        .log-container {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .log-entry {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="submit"]:focus {
            outline: none;
        }
        .back-button {
            display: block;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
        

    </style>
</head>
<body>
<div class="banner">
    <div class="navbar">
        <img src="logo.png" class="logo">
        <ul>
            <li><a href="index.html">Home</a></li>
            <li class="active"><a href="sadashboard.php">Dashboard</a></li>
            <li><a href="Profile.html">Profile</a></li>
            <li><a href="logout.php">Log Out</a></li>
        </ul>
    </div>
    <div class="dashboard-container">
        <?php if (isset($error_message)) : ?>
            <!-- Error message if case number not found -->
            <p><?php echo $error_message; ?></p>
            <a class="back-button" href="communication.php">Back</a>
        <?php else : ?>
            <!-- Display case details -->
            <h2>Case Log Interface - Case Number: <?php echo $case_number; ?></h2>
            <p>Petitioner Name: <?php echo $petitioner_name; ?></p>
            <p>Opposite Party: <?php echo $opposite_party; ?></p>
            <p>Case Status: <?php echo $case_status; ?></p>
            <p>Appointed Advocate: <?php echo $appointed_lawyer; ?></p>
            <p>Status Date: <?php echo $status_date; ?></p>

            <!-- Display existing case logs -->
            <h3>Existing Case Logs</h3>
            <div class="log-container">
                <?php
                if (!empty($case_logs)) {
                    foreach ($case_logs as $log) {
                        echo "<div class='log-entry'>";
                        echo  $log['log_date'];
                        echo "<p>Log : " . $log['log_text'] . "</p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No case logs found.</p>";
                }
                ?>
            </div>

            <!-- Form to add new case log -->
            <?php if ($user_role === 'senior advocate') : ?>
                <h3>Add New Case Log </h3>
                <form method="post">
                    <label for="log_text">Log:</label>
                    <textarea name="log_text" rows="4" <?php echo $readonly; ?> required></textarea><br>
                    <?php if ($readonly === "") : ?>
                        <input type="submit" value="Add Log">
                    <?php endif; ?>
                </form>
            <?php elseif ($user_role === 'junior advocate') : ?>
                <h3>Add New Case Log</h3>
                <form method="post">
                    <label for="log_text">Log Text:</label>
                    <textarea name="log_text" rows="4" <?php echo $readonly; ?> required></textarea><br>
                    <?php if ($readonly === "") : ?>
                        <input type="submit" value="Add Log">
                    <?php endif; ?>
                </form>
            <?php else : ?>
                <p>Only advocates can add case logs.</p>
            <?php endif; ?>
            
            <?php if ($user_role === 'client') : ?>
                <a class="back-button" href="clientview.php">Back</a>
            <?php else : ?>
                <a class="back-button" href="communication.php">Back</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
