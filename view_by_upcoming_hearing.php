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

<?php include 'header.html'; ?>
<div style="color: blue; margin-bottom: 10px; font-size: 25px;"><b>Upcoming Hearings</b> <br>
    <input type="button" onclick="window.location='return.php'" value="Back to Home">
</div>

<?php if ($num_of_rows > 0) { ?>
    <table style="border: 2px solid black;">
        <tr style="border: 2px solid black;">
            <td>S.No</td>
            <td>Case Number</td>
            <td>Date</td>
            <td>Versus (Petitioner vs Respondent)</td>
            <td>Advocate</td>
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
    <div style="margin-top: 20px; font-size: 20px;">No Upcoming Hearings Found</div>
    <div style="margin-top: 10px;"><center><input type="button" value="Back" onclick="window.location='view_by.php'"></center></div>
<?php } ?>

</div>
</center>
</body>
</html>
