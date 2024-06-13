<?php
session_start();
include ("connect.php");

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


.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
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

.tab {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.tab button {
    border-style: outset;
    cursor: pointer;
    padding: 5px 10px;
    background-color: #3399cc;
    color: #ffffff;
    margin: 0 5px;
}

.tab button:hover {
    background-color: #008cba;
}

.tab button.active {
    background-color: grey;
    color: white;
}

.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}

.tabcontent.active {
    display: block;
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
        <div class="container">
            <h1>Case Library</h1>
            <br><br><br>
            <div class="tab">
                <button class="tablinks" onclick="openType(event, 'View By Year')">Year</button>
                <button class="tablinks" onclick="openType(event, 'View By Case Number')">Case Number</button>
                <button class="tablinks" onclick="openType(event, 'View By Advocate Name')">Advocate Name</button>
                <button class="tablinks" onclick="window.location.href='view_by_upcoming_hearing.php'">Upcoming Hearings</button>
            </div>
            <div id="View By Year" class="tabcontent">
                <?php $_SESSION['var'] = "year"; ?>
                <form action="view_by_year.php" method="POST">
                    <label for="year"><b>Enter Year:</b></label>
                    <input type="text" name="yyyy" required placeholder="Year">
                    <input type="submit" name="submit1" value="Search">
                </form>
            </div>
            <div id="View By Case Number" class="tabcontent">
                <form action="view_case.php" method="POST">
                    <label for="case_number"><b>Enter Case Number:</b></label>
                    <input type="text" name="case_number" required placeholder="Case Number">
                    <input type="submit" name="submit2" value="Search">
                </form>
            </div>
            <div id="View By Advocate Name" class="tabcontent">
                <?php $_SESSION['var'] = "advocate_name"; ?>
                <form action="view_by_advocate_name.php" method="post">
                    <label for="advocate_name"><b>Select Advocate Name:</b></label>
                    <select name="advocate_name" required>
                        <option value="">--------</option>
                        <?php
                        // Connect to your database (replace dbname, username, password with your credentials)
                        $conn = new mysqli("localhost", "root", "", "sjdb");

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Query to fetch junior advocates' full names
                        $query = "SELECT full_name FROM users WHERE role = 'junior advocate'";
                        $result = $conn->query($query);

                        // Fetch and populate the drop-down menu with the full names of junior advocates
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                            }
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </select>
                    <input type="submit" name="submit3" value="Search">
                </form>
            </div>
        </div>
    </div>
<script>
function openType(evt, type) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(type).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
</body>
</html>

