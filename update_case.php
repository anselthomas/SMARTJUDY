<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Function to check if the user is a junior advocate
function isJuniorAdvocate($conn, $username)
{
    $query = "SELECT role FROM users WHERE username = '$username' AND role = 'junior advocate'";
    $result = $conn->query($query);
    return $result->num_rows > 0;
}

// Function to check if the case is appointed to the user
function isCaseAppointedToUser($conn, $case_number, $username)
{
    $query = "SELECT COUNT(*) as count FROM cases WHERE case_number = '$case_number' AND appointed_lawyer = '$username'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

$isJuniorAdvocate = false; // Initialize $isJuniorAdvocate as false by default

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and get the case_number
    $case_number = $_POST['case_number'];

    // Connect to your database (replace dbname, username, password with your credentials)
    $conn = new mysqli("localhost", "root", "", "legal1");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is a junior advocate
    $username = $_SESSION['username'];
    $isJuniorAdvocate = isJuniorAdvocate($conn, $username);

    // If the user is a junior advocate, disable the appointed_lawyer field
    $appointed_lawyer_disabled = $isJuniorAdvocate ? "disabled" : "required";

    // Check if the case is already appointed to the user
    $isCaseAppointedToUser = isCaseAppointedToUser($conn, $case_number, $username);

    if ($isJuniorAdvocate && !$isCaseAppointedToUser) {
        // Case is not appointed to the user
        echo "This is not a case appointed to you!!";
    } else {
        // Update the values in the database for the specified case_number
        $appointed_lawyer = $_POST['appointed_lawyer'];
        $responsible_officer = $_POST['responsible_officer'];
        $case_status = $_POST['case_status'];
        $status_date = $_POST['status_date'];
        $description = $_POST['description'];
        $remarks = $_POST['remarks'];

        // If the user is a junior advocate, set the appointed_lawyer to NULL
        if ($isJuniorAdvocate) {
            $appointed_lawyer = NULL;
        }

        $sql = "UPDATE cases 
                SET appointed_lawyer = '$appointed_lawyer',
                    responsible_officer = '$responsible_officer',
                    case_status = '$case_status',
                    status_date = '$status_date',
                    description = '$description',
                    remarks = '$remarks'
                WHERE case_number = '$case_number'";

        if ($conn->query($sql) === TRUE) {
            // Case updated successfully, redirect to another page or show a success message
            header("Location: sadashboard.php");
            exit();
        } else {
            // Error occurred while updating the case, you may handle the error appropriately
            echo "Error updating case: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Update Case</title>
    <!-- Add your CSS stylesheets, if any -->
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
            <h1>Update case</h1>
            <br><br><br>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="case_number">Case Number:</label>
                <input type="text" name="case_number" required>

                <?php if (!$isJuniorAdvocate) : ?>
                    <!-- If not a junior advocate, display the appointed_lawyer field -->
                    <label for="appointed_lawyer">Appointed Lawyer:</label>
                    <select name="appointed_lawyer" <?php echo $appointed_lawyer_disabled; ?>>
                        <option value="">--------</option>
                        <?php
                        // Connect to your database (replace dbname, username, password with your credentials)
                        $conn = new mysqli("localhost", "root", "", "legal1");

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Query to fetch advocate names with role='junior advocate'
                        $query = "SELECT full_name FROM users WHERE role = 'junior advocate'";
                        $result = $conn->query($query);

                        // Fetch and populate the drop-down menu with the advocate names
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                            }
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </select>
                <?php endif; ?>

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
