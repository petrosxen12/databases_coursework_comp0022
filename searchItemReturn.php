<?php

include "dbConnect.php";

function searchItem($conn, $item)
{
    $sql = "EXEC SearchItem white";
    // $params = array($item);
    $executed = sqlsrv_query($conn, $sql);

    if ($executed !== false) {
        echo ("executed\n");
        $res = sqlsrv_fetch_object($executed);

        if ($res === false) {
            die(print_r(sqlsrv_errors(), true));
            echo "Failed to fetch";
        }

        while ($res = sqlsrv_fetch_object($executed)) {
            echo <<<"EOT"
            <br>$res->EbayID</br>
            EOT;
        }
    } else {
        echo "Failed";
        die(print_r(sqlsrv_errors(), true));
    }
}

$conn = connectToDB();
// searchItem($conn, "iphone");

// ------------------------------------------------------------------------------------------

function searchForItem($conn, $item)
{
    $sql = "EXEC [dbo].[SearchItem] ?";
    $params = array(
        $item,
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

    /*Free the statement and connection resources. */
    //sqlsrv_free_stmt($stmt);
    //sqlsrv_close($conn);
}

// searchForItem($conn, "iphone");
searchItem($conn, "iphone");
