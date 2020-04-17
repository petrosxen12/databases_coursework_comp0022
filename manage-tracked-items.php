<?php 
include "connect-to-database.php";

function addTrackedItem($ebayId, $accountId, $priceLB) {
    $conn = connectToDB();
    $sql = "EXEC [dbo].[AddTrackedItem] @ebayId = ?, @accountId = ?, @priceLB = ?";
    $params = array(
        $ebayId,
        $accountId,
        $priceLB
    );
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if($stmt) {  
        echo "Statement prepared.\n";  
    }  
    else {  
        echo "Error in preparing statement.\n";  
        die( print_r( sqlsrv_errors(), true));  
    } 
    
    if(sqlsrv_execute( $stmt)) {  
        echo "Statement executed.\n";  
    }  
    else  
    {  
        echo "Error in executing statement.\n";  
        die( print_r( sqlsrv_errors(), true));  
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($dbConnection);
}

function removeTrackedItem($ebayId) {
    $conn = connectToDB();
    $sql = "EXEC [dbo].[DeleteTrackedItemEntry] @ebayId = ?";
    $params = array($ebayId);
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if($stmt) {  
        echo "Statement prepared.\n";  
    }  
    else {  
        echo "Error in preparing statement.\n";  
        die( print_r( sqlsrv_errors(), true));  
    } 
    
    if(sqlsrv_execute( $stmt)) {  
        echo "Statement executed.\n";  
    }  
    else  
    {  
        echo "Error in executing statement.\n";  
        die( print_r( sqlsrv_errors(), true));  
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}

?>