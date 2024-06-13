<?php
session_start();
include("connect.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT full_name, role, email_id, phn_no FROM users WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'];
    $role = $row['role'];
    $email_id = $row['email_id'];
    $phn_no = $row['phn_no'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Settings</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="Profile.css">
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="active"><a href="#">Dashboard</a></li>
                <li><a href="#">Log Out</a></li>
            </ul>
        </div>
        <section class="py-5 my-5">
            <div class="container">
                <h1 class="mb-5">PROFILE</h1>
                <div class="q123456">
                    <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name:</label>
                                        <div><?php echo $full_name; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <div><?php echo $email_id; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone number:</label>
                                        <div><?php echo $phn_no; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label>User Name:</label>
                                        <div><?php echo $username; ?></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Role:</label>
                                        <div><?php echo $role; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Update button centered below the container -->
                <div class="text-center mt-4">
                    <a href="updateprofile.php" class="btn123">Update</a>
                </div>
            </div>
        </section>
    </body>
</html>
