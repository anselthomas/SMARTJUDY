
<?php
session_start();
include 'connect.php';


if (isset($_POST['Login'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username ='$user_name' AND password ='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_type = $row['role'];

        $_SESSION['username'] = $user_name;

        if ($user_type == 'senior advocate') {
            header("Location: sadashboard.php");
            exit();
        } elseif ($user_type == 'junior advocate') {
            header("Location: jadashboard.php");
            exit();
        } elseif ($user_type == 'client') {
            header("Location: cdashboard.php");
            exit();
        }
    } else {
        echo "Invalid username or password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>smartjudy/login</title>
      <link rel="stylesheet" href="login.css">
   </head>
   <body>
      <div class="wrapper">
         <div class="title">
            Login Form
         </div>
         <form action="login.php" method="POST">
            <div class="field">
               <input type="text" name='user_name' required>
               <label>User Name</label>
            </div>
            <div class="field">
               <input type="password" name ='password' required>
               <label>Password</label>
            </div>
            <div class="field">
               <input type="submit" name="Login">
            </div>
            <div class="signup-link">
               Not a member? <a href="./register.php" target ="_blank">Signup now</a>
            </div>
         </form>
      </div>
   </body>
</html>
