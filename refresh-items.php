<?php 
include "connect-to-database.php";

function refreshDB($conn) {
    $sql = "EXEC [dbo].[RemoveUntrackedBids]";

    $stmt = sqlsrv_prepare($conn, $sql);
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

    //===========================================

    $sql = "EXEC [dbo].[RemoveUntrackedItems]";

    $stmt = sqlsrv_prepare($conn, $sql);
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
}
$conn = connectToDB();
refreshDB($conn);
?>