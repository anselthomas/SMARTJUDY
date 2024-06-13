<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<?php include 'header.html'; ?>
<div style="color: blue; margin-bottom: 10px; font-size: 25px;"><b><?php echo $_POST['advocate_name']; ?></b> <br>
    <input type="button" onclick="window.location='return.php'" value="Back to Home">
</div>

<?php
error_reporting(E_ERROR | E_PARSE);
include('connect.php');

function displayCaseStatusTable($query, $tableHeader)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    $num_of_rows = mysqli_num_rows($result);

    if ($num_of_rows > 0) {
        echo '<table style="border: 2px solid black;">';
        echo '<tr>';
        echo '<td colspan="3" style="border: 2px solid black;"><center>' . $tableHeader . '</center></td>';
        echo '</tr>';
        echo '<tr style="border: 2px solid black;">';
        echo '<td>S.No</td>';
        echo '<td>Case Number</td>';
        echo '<td>Description</td>';
        echo '<td>Status Date</td>';
        echo '</tr>';

        $sno = 1;
        while ($row = mysqli_fetch_array($result)) {
            echo '<tr>';
            echo '<td>' . $sno . '</td>';
            echo '<td>' . $row['case_number'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['status_date'] . '</td>';
            echo '</tr>';
            $sno++;
        }

        echo '</table>';
        echo '<br><br>';
    } else {
        echo 'No Data Found for ' . $tableHeader . '<br><br>';
    }
}

$query_pending = "SELECT * FROM cases WHERE appointed_lawyer = '{$_POST['advocate_name']}' AND case_status = 'pending' ORDER BY case_year ASC";
displayCaseStatusTable($query_pending, 'Case Pending');

$query_hearing = "SELECT * FROM cases WHERE appointed_lawyer = '{$_POST['advocate_name']}' AND case_status = 'Hearing' ORDER BY case_year ASC";
displayCaseStatusTable($query_hearing, 'Case Hearing');

$query_arg_pending = "SELECT * FROM cases WHERE appointed_lawyer = '{$_POST['advocate_name']}' AND case_status = 'Arguement Pending' ORDER BY case_year ASC";
displayCaseStatusTable($query_arg_pending, 'Case Arguement Pending');

$query_disposed = "SELECT * FROM cases WHERE appointed_lawyer = '{$_POST['advocate_name']}' AND case_status = 'Disposed' ORDER BY case_year ASC";
displayCaseStatusTable($query_disposed, 'Case Disposed');
?>

<div style="text-align: center;">
    <form>
        <input type="button" value="Back" onclick="window.location='view_by.php'">
    </form>
</div>
</div>
</center>
</body>
</html>
