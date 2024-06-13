<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
error_reporting(E_ERROR | E_PARSE);

$msg = "";
include('connect.php');

$query1 = "SELECT DISTINCT case_type FROM cases WHERE case_year='{$_POST['yyyy']}'";
$result1 = mysqli_query($conn, $query1);
$num_of_rows = mysqli_num_rows($result1);

// Initialize sum variables
$num_of_rows_sum_total = 0;
$num_of_rows_sum_pending = 0;
$num_of_rows_sum_disposed = 0;

if ($num_of_rows > 0) {
    while ($row = mysqli_fetch_array($result1)) {
        $temp_array[] = $row;
    }
} else {
    $msg = "No Message Found";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cases in Years</title>
    <link rel="stylesheet" type="text/css" href="clientview.css" />
    <style>
        
    </style>
</head>
<body>
    <center>
            <div style="color: blue; margin-bottom: 10px; font-size: 25px;"> <b>YEAR : <?php echo $_POST['yyyy']; ?></b>
                <br>
                <input type="button" onclick="window.location='return.php'" value="Back to Home">
            </div>

            <table>
                <tr>
                    <th>Category</th>
                    <th>Total Cases Filed</th>
                    <th>Total Cases Pending</th>
                    <th>Total Cases Disposed</th>
                    <th>Remarks</th>
                </tr>

                <?php
                $j = 0;
                for ($j = 0; $j < $num_of_rows; $j++) {
                    $row = $temp_array[$j];
                    $var = $row['case_type'];

                    $query_total_case = "SELECT * FROM cases WHERE case_type = '$var' AND case_year='{$_POST['yyyy']}'";
                    $result_total_case = mysqli_query($conn, $query_total_case);
                    $num_of_rows_total = mysqli_num_rows($result_total_case);
                    $num_of_rows_sum_total += $num_of_rows_total;

                    $query_pending_case = "SELECT * FROM cases WHERE case_type = '$var' AND case_status='pending' AND case_year='{$_POST['yyyy']}'";
                    $result_pending_case = mysqli_query($conn, $query_pending_case);
                    $num_of_rows_pending = mysqli_num_rows($result_pending_case);
                    $num_of_rows_sum_pending += $num_of_rows_pending;

                    $query_disposed_case = "SELECT * FROM cases WHERE case_type = '$var' AND case_status='disposed' AND case_year='{$_POST['yyyy']}'";
                    $result_disposed_case = mysqli_query($conn, $query_disposed_case);
                    $num_of_rows_disposed = mysqli_num_rows($result_disposed_case);
                    $num_of_rows_sum_disposed += $num_of_rows_disposed;
                ?>

                    <tr>
                        <td><?php echo $var; ?></td>
                        <td><?php echo $num_of_rows_total; ?></td>
                        <td><?php echo $num_of_rows_pending; ?></td>
                        <td><?php echo $num_of_rows_disposed; ?></td>
                        <td><?php echo $_POST['remarks']; ?></td>
                    </tr>

                <?php
                }
                ?>
            </table>
        </div>
    </center>
</body>
</html>
