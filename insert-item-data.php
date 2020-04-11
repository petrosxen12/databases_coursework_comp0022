<?php 

function writeDataToDB($conn, $item) {
    $tsql_callSP = "{call InsertItemData(?,?,?,?,?,?,?,?,?,?,?,?)}";
    $params = array(
        array($item->title, SQLSRV_PARAM_IN),
        array($item->itemId, SQLSRV_PARAM_IN),
        array($item->viewItemURL, SQLSRV_PARAM_IN),
        array(NULL, SQLSRV_PARAM_IN), //TODO
        array(NULL, SQLSRV_PARAM_IN), //TODO
        //array($item->sellerInfo->sellerUserName, SQLSRV_PARAM_IN),
        //array($item->sellerInfo->feedbackScore, SQLSRV_PARAM_IN),
        array($item->condition->conditionDisplayName, SQLSRV_PARAM_IN),
        array($item->listingInfo->listingType, SQLSRV_PARAM_IN),
        array($item->sellingStatus->currentPrice, SQLSRV_PARAM_IN),
        array(NULL, SQLSRV_PARAM_IN), //TODO
        array(NULL, SQLSRV_PARAM_IN), //TODO
        array(NULL, SQLSRV_PARAM_IN), //TODO
        array(NULL, SQLSRV_PARAM_IN)  //TODO
    );
    $stmt = sqlsrv_query($conn, $tsql_callSP, $params);
    if (!$stmt) {
        echo "Error in executing statement.\n";
        die( print_r( sqlsrv_errors(), true));
    }
    else {
        echo("Data inserted successfully!");
    }

    /*Free the statement and connection resources. */
    sqlsrv_free_stmt($stmt3);
    sqlsrv_close($conn);
}

?>