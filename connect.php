<?php 
$servername ="localhost";
$username ="root";  
$password ="";
$dbname ="legal1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if($conn) {
	//echo "<br>connection successfull";
}
else {
echo "Error Connecting to the database";
}	
?>