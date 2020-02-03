<?php
    
    $serverName = "dbserverxen.database.windows.net"; // update me
    $connectionOptions = array(
        "Database" => "dbTesting", // update me
        "Uid" => "xen@dbserverxen", // update me
        "PWD" => "malakas123!@" // update me
    );
    //Establishes the connection
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    $tsql= "SELECT TOP (1000) * FROM [dbo].[Persons]";
    $getResults= sqlsrv_query($conn, $tsql);
    echo ("Reading data from table" . PHP_EOL);
    if ($getResults == FALSE)
        echo (sqlsrv_errors());

    $row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)
    
    // while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    //  echo("<h3>");
    //  echo ($row['LastName'] . " " . $row['FirstName'] . PHP_EOL);
    //  echo("</h3>");
    // }

    sqlsrv_free_stmt($getResults);
    
?>