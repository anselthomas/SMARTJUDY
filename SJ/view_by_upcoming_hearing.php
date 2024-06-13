<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
error_reporting(E_ERROR | E_PARSE);
$msg = "";

include('connect.php');

// Get the current date
$date = date("Y-m-d");

$query_upcoming_hearings = "SELECT * FROM cases WHERE status_date > '$date' AND case_status = 'Hearing' ORDER BY status_date ASC";
$result_upcoming_hearings = mysqli_query($conn, $query_upcoming_hearings);
$num_of_rows = mysqli_num_rows($result_upcoming_hearings);

$temp_array = array();
if ($num_of_rows > 0) {
    while ($row = mysqli_fetch_array($result_upcoming_hearings)) {
        $temp_array[] = $row;
    }
} else {
    $msg = "No Upcoming Hearings Found";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Legal file System</title>
<style type="text/css">
/* generic css */
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

.container {
    margin: auto;
    width: 800px;
    height: 550px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    padding: 10px;
    
    text-align: center;
    color: white;
    background-color: transparent;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: ;
}

.tab {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.upcoming-hearings {
    color: blue;
    margin-bottom: 10px;
    font-size: 25px;
}

.upcoming-hearings b {
    font-weight: bold;
}

.upcoming-hearings input[type="button"] {
   
}

.upcoming-hearings-table {
    border: 2px solid black;
   
}

.upcoming-hearings-table th,
.upcoming-hearings-table td {
    border: 2px solid black;

}

.upcoming-hearings-table td {
   
}

.upcoming-hearings-table th {
   
}

.upcoming-hearings-message {
    margin-top: 20px;
    font-size: 20px;
  
}

.back-button {
    margin-top: 10px;
    text-align: center;
 
}


</style>
</head>
<body>
    <div class="banner">
        <div class="navbar">
            <img src="logo.png" class="logo">
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="Profile.html">Profile</a></li>
                <li><a href="logout.php">Log Out</a></li>
            </ul>
        </div>
        <div class="container">
            <b>Upcoming Hearings</b> <br> 
            <?php if ($num_of_rows > 0) { ?>
            <table class="upcoming-hearings-table">
                <tr>
                    <th>S.No</th>
                    <th>Case Number</th>
                    <th>Date</th>
                    <th>Versus (Petitioner vs Respondent)</th>
                    <th>Advocate</th>
                </tr>

                <?php
                $j = 0;
                $sno = 1;
                foreach ($temp_array as $row) {
                    $case_num = $row['case_number'];
                    $date1 = $row['status_date'];
                    $versus = $row['opposite_party'];
                    $petitioner = $row['petitioner_name'];
                    $advocate = $row['appointed_lawyer'];
                ?>
                    <tr>
                        <td><?php echo $sno; ?></td>
                        <td><?php echo $case_num; ?></td>
                        <td><?php echo $date1; ?></td>
                        <td><?php echo $petitioner . ' vs ' . $versus; ?></td>
                        <td><?php echo $advocate; ?></td>
                    </tr>
                <?php
                    $sno++;
                }
                ?>

                <tr>
                    <td colspan="5"><center><input type="button" value="Back" onclick="window.location='view_by.php'"></center></td>
                </tr>
            </table>
            <?php } else { ?>
                <div class="upcoming-hearings-message">No Upcoming Hearings Found</div>
                <div class="back-button"><center><input type="button" value="Back" onclick="window.location='view_by.php'"></center></div>
            <?php } ?>   
        </div>
    </div>
</body>
</html>
