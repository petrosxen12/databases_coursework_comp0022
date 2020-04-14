<?php 

function formatTime($item) {
    $date = new DateTime();
    $date->add(new DateInterval($item->sellingStatus->timeLeft));
    $formatted = $date->format('Y-m-d H:i:s');
    //$formatted = $date->format('H:i:s');
    return $formatted;
}

function writeDataToDB($conn, $item) {
    $sql = "EXEC [dbo].[InsertItemData] 
                                    @title = ?,
                                    @ebayId	= ?,
                                    @url = ?,
                                    @seller	= ?,
                                    @sellerScore = ?,
                                    @condition = ?,
                                    @itemType = ?,
                                    @auctionPrice = ?,
                                    @bidDuration = ?,
                                    @auctionDealiness = ?,
                                    @buyNowPrice = ?,
                                    @buyNowDealiness = ?";
    $params = array($item->title, 
                    $item->itemId,
                    $item->viewItemURL, 
                    $item->sellerInfo->sellerUserName,
                    $item->sellerInfo->feedbackScore,
                    $item->condition->conditionDisplayName, 
                    $item->listingInfo->listingType, 
                    $item->sellingStatus->currentPrice->value, 
                    formatTime($item),
                    NULL, 
                    $item->listingInfo->buyItNowPrice,
                    NULL  //TODO
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

    /*Free the statement and connection resources. */
    //sqlsrv_free_stmt($stmt);
    //sqlsrv_close($conn);
}

?>