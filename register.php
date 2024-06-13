<?php
include("connect.php");

$showError = false; // Variable to track whether to show the error messages

if (isset($_POST['Register'])) {
  $full_name = $_POST['full_name'];
  $user_name = $_POST['user_name'];
  $email_id = $_POST['email_id'];
  $phn_no = $_POST['phn_no'];
  $password = $_POST['password'];
  $user_type = $_POST['user_type'];
  $confirm_password = $_POST['confirm_password'];

  // Validate email format using filter_var
  if (!filter_var($email_id, FILTER_VALIDATE_EMAIL)) {
    $showError = true;
    $emailError = "Invalid email format!";
  }

  // Validate phone number to have exactly 10 digits
  if (strlen($phn_no) !== 10 || !ctype_digit($phn_no)) {
    $showError = true;
    $phoneError = "Invalid phone number! It should have exactly 10 digits.";
  }

  if ($password !== $confirm_password) {
    $showError = true;
    $passwordError = "Passwords do not match!";
  }

  // Check if username already exists
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $user_name);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $showError = true;
    $usernameError = "Username already exists!";
  }

  // If there are no errors, proceed with registration
  if (!$showError) {
    $stmt = $conn->prepare("INSERT INTO users (full_name, username, email_id, phn_no, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $full_name, $user_name, $email_id, $phn_no, $password, $user_type);

    if ($stmt->execute()) {
      echo "Registration successful!";
      header("Location: login.php");
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }

    $stmt->close();
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>smartjudy/register</title>
  <link rel="stylesheet" href="register.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="container">
    <div class="title">Registration</div>
    <div class="content">
      <form action="register.php" method="POST">
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="full_name" placeholder="Enter your name" required>
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" name="user_name" placeholder="Enter your username" required>
            <?php if (isset($usernameError)) { echo "<span style='color: red;'>$usernameError</span>"; } ?>
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" name="email_id" placeholder="Enter your email" required>
            <?php if (isset($emailError)) { echo "<span style='color: red;'>$emailError</span>"; } ?>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="tel" name="phn_no" pattern="[0-9]{10}" placeholder="Enter your number" required>
            <?php if (isset($phoneError)) { echo "<span style='color: red;'>$phoneError</span>"; } ?>
          </div>
          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
            <?php if (isset($passwordError)) { echo "<span style='color: red;'>$passwordError</span>"; } ?>
          </div>
        </div>
        <div class="user-details">
          <input type="radio" name="user_type" id="dot-1" value="senior advocate">
          <input type="radio" name="user_type" id="dot-2" value="junior advocate">
          <input type="radio" name="user_type" id="dot-3" value="client">
          <span class="user-title">User</span>
          <div class="category">
            <label for="dot-1">
            <span class="dot one"></span>
            <span class="user">Senior Advocate</span>
            </label>
            <label for="dot-2">
            <span class="dot two"></span>
            <span class="user">Junior Advocate</span>
            </label>
            <label for="dot-3">
            <span class="dot three"></span>
            <span class="user">Client</span>
            </label>
          </div>
        </div>
        <div class="button">
          <input type="submit" name="Register">
        </div>
        <div class="login-link">
          Already a member? <a href="./login.php" target ="_blank">Signup now</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
