<?php
// // PHP Data Objects(PDO) Sampl
require_once("dbConnect.php");

$sql = "SELECT AccountID FROM Accounts WHERE Email = (?)";

// Set parameters
$param_email = array("test5@email.co.uk");
$stmt = sqlsrv_query($conn, $sql, $param_email);

if ($stmt) {
    print(sqlsrv_num_rows($stmt));
    print("Statement executed");
} else {
    print("Could not execute statement");
}
