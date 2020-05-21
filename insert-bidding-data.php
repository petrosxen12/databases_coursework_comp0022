<?php 

function writeBiddingData($conn, $ebayId, $username, $bidAmount, $time) {
    $sql = "EXEC [dbo].[AddBids] 
                                    @ebayId = ?,
                                    @username = ?,
                                    @bidAmount	= ?,
                                    @time = ?";
    
    $params = array($ebayId,
                    $username, 
                    $bidAmount,
                    $time
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
}
?>