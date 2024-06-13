<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if the case number is provided via POST
if (isset($_POST['submit2']) && isset($_POST['case_number'])) {
    $case_number = $_POST['case_number'];
    $_SESSION['case_number'] = $case_number;

    include('connect.php');

    // Fetch the case details using the provided case number
    $fetch_query = "SELECT * FROM cases WHERE case_number = '$case_number'";
    $fetch_result = mysqli_query($conn, $fetch_query);
    if (mysqli_num_rows($fetch_result) > 0) {
        $row = mysqli_fetch_assoc($fetch_result);

        // Assign the fetched data to variables
        $petitioner_name = $row['petitioner_name'];
        $opposite_party = $row['opposite_party'];
        $appointed_lawyer = $row['appointed_lawyer'];
        $responsible_officer = $row['responsible_officer'];
        $case_status = $row['case_status'];
        $status_date = $row['status_date'];
        $description = $row['description'];
        $remarks = $row['remarks'];
        $document_path = $row['document_path'];
    } else {
        // Case number not found in the database
        $error_message = "Case number not found in the database.";
    }
} else {
    // Case number is not provided
    $error_message = "Case number is not provided.";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Legal File System</title>
    <link rel="stylesheet" type="text/css" href="clientview.css" />
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        center {
            margin-top: 50px;
        }

        .container {
            width: 700px;
            background-color: #fff;
            border-collapse: collapse;
            margin: auto;
        }

        .container th,
        .container td {
            border: 1px solid #333;
            padding: 10px;
        }

        .container th {
            background-color: #aa22cc; /* Purple-pink color */
            color: #fff;
            text-align: left;
        }

        .container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .container tr:hover {
            background-color: #ee66dd; /* Lighter purple-pink color on hover */
        }

        input[type="button"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        input[type="button"]:hover {
            background-color: #0056b3;
        }

        input[type="button"]:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <center>
        <?php if (isset($error_message)) : ?>
            <p><?php echo $error_message; ?></p>
            <input type="button" value="Back" onclick="window.location='view_by.php'">
        <?php else : ?>
            <div class="container">
                <table>
                    <!-- Table header -->
                    <tr>
                        <th>Case Number</th>
                        <td><?php echo $case_number; ?></td>
                    </tr>
                    <tr>
                        <th>Petitioner Name</th>
                        <td><?php echo $petitioner_name; ?></td>
                    </tr>
                    <tr>
                        <th>Opposite Party</th>
                        <td><?php echo $opposite_party; ?></td>
                    </tr>
                    <tr>
                        <th>Appointed Lawyer</th>
                        <td><?php echo $appointed_lawyer; ?></td>
                    </tr>
                    <tr>
                        <th>Responsible Officer</th>
                        <td><?php echo $responsible_officer; ?></td>
                    </tr>
                    <tr>
                        <th>Case Status</th>
                        <td><?php echo $case_status; ?></td>
                    </tr>
                    <tr>
                        <th>Status Date</th>
                        <td><?php echo $status_date; ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?php echo $description; ?></td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <td><?php echo $remarks; ?></td>
                    </tr>
                    <tr>
                        <th>Document Path</th>
                        <td><?php echo $document_path; ?></td>
                    </tr>
                </table>
            </div>
            <input type="button" value="Back" onclick="window.location='view_by.php'">
        <?php endif; ?>
    </center>
</body>
</html>
