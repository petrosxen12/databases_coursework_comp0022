<?php

include "dbConnect.php";

$conn = connectToDB();
// searchItem($conn, "iphone");

// ------------------------------------------------------------------------------------------
function searchItem($conn, $keyword)
{
    $sql = "EXEC [dbo].[SearchItemByKeyword] @keyword = ?";
    //$output = [];
    $params = array(
        array($keyword, SQLSRV_PARAM_IN)
        //array(&$output, SQLSRV_PARAM_OUT)
    );
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt) {
        echo "Statement prepared.\n";
    } else {
        echo "Error in preparing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($stmt)) {
        echo "Statement executed.\n";
    } else {
        echo "Error in executing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    /* Retrieve each row as an associative array and display the results.*/
    // while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //     echo $row['Title'] . "\n";
    // }
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    return $row;
}

// $connection = connectToDB();
// searchItem($connection, "iphone");
