<!DOCTYPE html>
<html>
<head>
    <title>Advocate Interface</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #000; /* Add blackline segregation between columns */
        }

        thead {
            background-color: #3a0954;
            color: white; /* Change the text color for the header */
        }

        tbody tr {
            background-color: transparent;
        }

        tbody tr:hover {
            background-color: #892eaa;
        }

        a {
            color: white;
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        /* New styles for the "Cases" table */
        table {
            border: 2px solid #0b0b0b;
        }

        th {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        td {
            font-size: 16px;
        }
        </style>
</head>
<body>
    
</body>
</html>


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
        <h1>CASES</h1>
        <br><br><br>
        <h2>Civil Cases</h2>
        <table>
            <thead>
                <tr>
                    <th>Case Number</th>
                    <th>Plaintiff Name</th>
                    <th>Incident Date</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include the database connection
                include 'connect.php';

                // Function to retrieve civil cases from the database
                function getCivilCases($conn)
                {
                    $sql = "SELECT * FROM civil_case_details";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['case_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['plaintiff_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['incident_date']) . "</td>";
                            echo "<td><a href='viewcase.php?case_type=civil&case_number=" . urlencode($row['case_number']) . "'>View</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No civil cases found.</td></tr>";
                    }
                }

                // Display civil cases
                getCivilCases($conn);
                ?>
            </tbody>
        </table>
        <br><br>
        <h2>Criminal Cases</h2>
        <table>
            <thead>
                <tr>
                    <th>Case Number</th>
                    <th>Accused Name</th>
                    <th>Incident Date</th>
                    <th>View Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Function to retrieve criminal cases from the database
                function getCriminalCases($conn)
                {
                    $sql = "SELECT * FROM criminal_case_details";
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['case_number']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['accused_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['incident_date']) . "</td>";
                            echo "<td><a href='view_case.php?case_type=criminal&case_number=" . urlencode($row['case_number']) . "'>View</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No criminal cases found.</td></tr>";
                    }
                }

                // Display criminal cases
                getCriminalCases($conn);
                ?>
            </tbody>
        </table>
    </div>
</div>
