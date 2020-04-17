<?php

function addTrackedItem($conn, $ebayId, $accountId, $priceLB)
{
    $sql = "EXEC [dbo].[AddTrackedItem] @ebayId = ?, @accountId = ?, @priceLB = ?";
    $params = array(
        $ebayId,
        $accountId,
        $priceLB
    );
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt) {
        // echo "Statement prepared.\n";
    } else {
        // echo "Error in preparing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($stmt)) {
        // echo "Statement executed.\n";
        return true;
    } else {
        // echo "Error in executing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}


function removeTrackedItem($conn, $ebayId, $accountId)
{
    $conn = connectToDB();
    $sql = "EXEC [dbo].[DeleteTrackedItemEntry] @ebayId = ?, @accountId = ?";
    $params = array($ebayId, $accountId);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt) {
        // echo "Statement prepared.\n";
    } else {
        // echo "Error in preparing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($stmt)) {
        return true;
        // echo "Statement executed.\n";  
    } else {
        // echo "Error in executing statement.\n";  
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
