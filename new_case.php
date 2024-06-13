<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<?php include 'header.html'; ?>

<span style="color: blue; margin-bottom: 10px; font-size: 25px;"><b>File an Extended Case</b><br></span>

<form action="new_case_handler.php" method="POST">
    <table>
        <tr>
            <td><span style="float: left">Case Number / Reference Number / Review / Appeal:</span></td>

            <td>
                <table>
                    <tr>
                        <td>
                            <select name="case_type" required>
                                <option value="">Case Type</option>
                                <option value="AA">AA</option>
                                <option value="AC">AC</option>
                                <option value="AR">AR</option>
                                <!-- Add more case types as needed -->
                            </select>&nbsp;&nbsp;
                        </td>
                        <td>
                           
                        </td>
                        <td>
                            <select name="case_year" required>
                                <option value="">Year</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <!-- Add more years as needed -->
                            </select>&nbsp;&nbsp;

                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <div style="float: left">Petitioner Name:</div>
            </td>

            <td>
                <input type="text" name="petitioner_name" required>
            </td>
        </tr>

        <tr>
            <td>
                <div style="float: left">Respondent Name:</div>
            </td>

            <td>
                <input type="text" name="versus" required>
            </td>
        </tr>
<!-- ... Existing code ... -->

<tr>
    <td>
        <div style="float: left">Name of Appointed Advocate:</div>
    </td>

    <td>
        <select name="advocate_name" required>
            <option value="">--------</option>
            <?php
            // Connect to your database (replace dbname, username, password with your credentials)
            $conn = new mysqli("localhost", "root", "", "legal1");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to fetch advocate names
            $query = "SELECT full_name FROM users WHERE role = 'junior advocate'";
            $result = $conn->query($query);

            // Fetch and populate the drop-down menu with the advocate names
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['full_name'] . '">' . $row['full_name'] . '</option>';
                }
            }

            // Close the database connection
            $conn->close();
            ?>
        </select>
    </td>
</tr>

<!-- ... Remaining code ... -->

        <tr>
            <td>
                <div style="float: left">Name of Responsible Officer:</div>
            </td>

            <td>
                <input type="text" name="officer_name" required>
            </td>
        </tr>

        <tr>
            <td>
                <div style="float: left">Case Status:</div>
            </td>

            <td>
                <table>
                    <tr>
                        <td>
                            <select name="case_status" required>
                                <option value="">Select Case Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Argument">Argument</option>
                                <option value="Hearing">Hearing</option>
                                <option value="Disposed">Disposed</option>
                                <!-- Add more case status options as needed -->
                            </select>&nbsp;&nbsp;
                        </td>

                        <td>
                            <select name="status_day" required>
                                <option value="">Day</option>
                                <option value="01">1</option>
                                <!-- Add days as needed -->
                            </select>&nbsp;&nbsp;
                        </td>

                        <td>
                            <select name="status_month" required>
                                <option value="">Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <!-- Add more months as needed -->
                            </select>&nbsp;&nbsp;
                        </td>

                        <td>
                            <select name="status_year" required>
                                <option value="">Year</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <!-- Add more years as needed -->
                            </select>&nbsp;&nbsp;
                        </td>

                    </tr>
                </table>
            </td>
        </tr>


        <tr>
            <td>
                <div style="float: left">Description:</div>
            </td>

            <td>
                <textarea name="description" rows="5" cols="40" required></textarea>
            </td>
        </tr>

        <tr>
            <td>
                <div style="float: left; margin-bottom: 5px;">Remarks:</div>
            </td>

            <td>
                <textarea name="remarks" rows="3" cols="40" required></textarea>
            </td>
        </tr>

        <tr>
            <td></td>

            <td>
                <input type="submit" name="submit" required>
            </td>
        </tr>
    </table>
</form>

</body>
</html>
