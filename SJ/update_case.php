<!DOCTYPE html>
<html>
<head>
    <title>Update Case</title>
    <style type="text/css">
        /* Your CSS styles here */
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
            <h1>Update case</h1>
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

        /* Style the form submit button */
        .dashboard-container form input[type="submit"] {
            background-color: #3399cc;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        .dashboard-container form input[type="submit"]:hover {
            background-color: #008cba;
        }
    </style>
            <br><br><br>
            <?php
            session_start();
            if (!isset($_SESSION['username'])) {
                header("Location: index.php");
                exit();
            }

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $case_number = $_POST['case_number'];
                $conn = new mysqli("localhost", "root", "", "legal1");

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $username = $_SESSION['username'];

                // Fetch the full name of the present user from the database.
                $full_name1 = "";
                $queryFullName = "SELECT full_name FROM users WHERE username = '$username'";
                $resultFullName = $conn->query($queryFullName);
                if ($resultFullName->num_rows > 0) {
                    $rowFullName = $resultFullName->fetch_assoc();
                    $full_name1 = $rowFullName['full_name'];
                }
                echo '<script>alert("' . $full_name1 . '");</script>';

                $appointed_lawyer_disabled = "required";

                $sql = "UPDATE cases 
                        SET appointed_lawyer = ?, 
                            responsible_officer = ?, 
                            case_status = ?, 
                            status_date = ?, 
                            description = ?, 
                            remarks = ?
                        WHERE case_number = ?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssssss",
                    $_POST['appointed_lawyer'],
                    $_POST['responsible_officer'],
                    $_POST['case_status'],
                    $_POST['status_date'],
                    $_POST['description'],
                    $_POST['remarks'],
                    $case_number
                );

                if ($stmt->execute()) {
                    header("Location: sadashboard.php");
                    exit();
                } else {
                    echo "Error updating case: " . $conn->error;
                }

                $stmt->close();
                $conn->close();
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="case_number">Case Number:</label>
                <input type="text" name="case_number" required>

                <label for="appointed_lawyer">Appointed Lawyer:</label>
                <select name="appointed_lawyer" <?php echo $appointed_lawyer_disabled; ?>>
                    <option value="">--------</option>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "legal1");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    $queryJunior = "SELECT full_name FROM users WHERE role = 'junior advocate'";
                    $resultJunior = $conn->query($queryJunior);

                    if ($resultJunior->num_rows > 0) {
                        while ($row = $resultJunior->fetch_assoc()) {
                            echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                        }
                    }
                    $queryJunior = "SELECT full_name FROM users WHERE role = 'junior advocate'";
                    $resultJunior = $conn->query($queryJunior);
                    $username = $_SESSION['username'];

                // Fetch the full name of the present user from the database.
                $full_name1 = "";
                $queryFullName = "SELECT full_name FROM users WHERE username = '$username'";
                $resultFullName = $conn->query($queryFullName);
                if ($resultFullName->num_rows > 0) {
                    $rowFullName = $resultFullName->fetch_assoc();
                    $full_name1 = $rowFullName['full_name'];
                }

                    if ($full_name1 !== "") {
                        // Show the full name of the present user as an option in the drop-down menu.
                        echo '<option value="' . $full_name1 . '">' . $full_name1 . '</option>';
                    }

                    $conn->close();
                    ?>
             
                </select>

                <label for="responsible_officer">Responsible Officer:</label>
                <input type="text" name="responsible_officer" required>

                <label for="case_status">Case Status:</label>
                <input type="text" name="case_status" required>

                <label for="status_date">Status Date:</label>
                <input type="date" name="status_date" required>

                <label for="description">Description:</label>
                <textarea name="description" required></textarea>

                <label for="remarks">Remarks:</label>
                <textarea name="remarks" required></textarea>

                <input type="submit" value="Update Case">
            </form>
        </div>
    </div>
</body>
</html>

