<?php
session_start();
include("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST["full_name"];
    $email_id = $_POST["email_id"];
    $phn_no = $_POST["phn_no"];
    $user_name = $_SESSION["username"]; // Get the session username
    $password = $_POST["password"];
    $newPassword = $_POST["new_password"];

    // Check if the new username is already taken
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, update the profile
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, email_id = ?, phn_no = ?, password = ? WHERE username = ?");
        $stmt->bind_param("sssss", $full_name, $email_id, $phn_no, $newPassword, $user_name);
        if ($stmt->execute()) {
            echo "Profile updated successfully!";
            header("Location: Profile.php");
        } else {
            echo "Error updating profile: " . $stmt->error;
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Profile</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="profile.css">
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
                <h1 class="mb-5">UPDATE PROFILE</h1>
                <div class="q123456">
                    <div class="tab-content p-4 p-md-5" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                            <form action="updateprofile.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" name="full_name" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" name="email_id" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Phone number</label>
                                            <input type="text" class="form-control" name="phn_no" value="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" class="form-control" name="user_name" value="<?php echo $_SESSION["username"]; ?>" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" class="form-control" name="new_password">
                                        </div>
                                    </div>
                                    <div>
                                    <a> <button type="submit" href="Profile.php"  class="btn123" name="submit">Submit</button></a> 
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
