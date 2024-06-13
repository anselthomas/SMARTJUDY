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
    <title>Legal File System</title>
    <script src="hindi.js"></script>
    <link rel="stylesheet" type="text/css" href="view.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        .awDiv {
            background-color: #CCCCFF;
            color: #000033;
            margin-left: 5px;
            font-size: 10pt;
        }

        .awSpan2 {
            margin-left: 20px;
            width: 50px;
            font-weight: bold;
        }

        .awSpan1 {
            margin-left: 20px;
            width: 10px;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td {
            text-align: center;
        }
    </style>
</head>
<body style="background-color:#ffff99;">
    <center>
        <div style="border: 5px solid black; width: 950px; height: 900px;">
            <div Style="font-size: 45px; margin-top: 20px; color: red"><b>Case Management System</b></div>
            <img src="law.png" alt="logo" style="width: 130px; height: 130px; margin-bottom: 15px;">
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

                    $query_total_case = "SELECT * FROM cases WHERE case_type = '$var'";
                    $result_total_case = mysqli_query($conn, $query_total_case);
                    $num_of_rows_total = mysqli_num_rows($result_total_case);

                    $query_pending_case = "SELECT * FROM cases WHERE case_type = '$var' AND case_status='pending'";
                    $result_pending_case = mysqli_query($conn, $query_pending_case);
                    $num_of_rows_pending = mysqli_num_rows($result_pending_case);

                    $query_disposed_case = "SELECT * FROM cases WHERE case_type = '$var' AND case_status='disposed'";
                    $result_disposed_case = mysqli_query($conn, $query_disposed_case);
                    $num_of_rows_disposed = mysqli_num_rows($result_disposed_case);
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

                <tr>
                    <td>Sum</td>
                    <td><?php echo $num_of_rows_sum_total; ?></td>
                    <td><?php echo $num_of_rows_sum_pending; ?></td>
                    <td><?php echo $num_of_rows_sum_disposed; ?></td>
                    <td><?php echo $_POST['remarks']; ?></td>
                </tr>
            </table>
        </div>
    </center>
</body>
</html>
