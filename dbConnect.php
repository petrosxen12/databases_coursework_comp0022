<?php
$host = 'ebaytool.mysql.database.azure.com';
$username = 'xen@ebaytool';
$pass = 'malakas123!@';
$db_name = 'users';

//Establishes the connection
$conn = mysqli_init();
mysqli_real_connect($conn, $host, $username, $pass, $db_name, 3306);
if (mysqli_connect_errno($conn)) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
}
    /*
    $tsql= "SELECT TOP (1000) * FROM [dbo].[Persons]";
    $getResults= sqlsrv_query($conn, $tsql);
    echo ("Reading data from table" . PHP_EOL);
    if ($getResults == FALSE)
        echo (sqlsrv_errors());
    */

    
    // $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)
    
    // while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    //  echo("<h3>");
    //  echo ($row['LastName'] . " " . $row['FirstName'] . PHP_EOL);
    //  echo("</h3>");
    // }

    // sqlsrv_free_stmt($getResults);
