<?php

include "dbConnect.php";

function searchItem($conn, $item)
{
    $sql = "EXEC SearchItem ?";
    $params = array("iphone");

    $executed = sqlsrv_query($conn, $sql, $params);
    if ($executed !== false) {
        echo ("executed");
        $res = sqlsrv_fetch_object($executed);
        echo ($res);
        while ($res = sqlsrv_fetch_object($executed)) {
            echo $res->colName;
        }
    }

    // if (sqlsrv_num_rows($executed) > 0) {
    //     echo "Statement executed.\n";
    //     while ($row = sqlsrv_fetch_array($executed)) {
    //         echo ("id: " . $row["ebayID"]);
    //     }
    //     echo "End";
    // } else {
    //     echo "Error in executing statement.\n";
    //     die(print_r(sqlsrv_errors(), true));
    // }
    /*Free the statement and connection resources. */
    //sqlsrv_free_stmt($stmt);
    //sqlsrv_close($conn);
}

$conn = connectToDB();
searchItem($conn, "iphone");
