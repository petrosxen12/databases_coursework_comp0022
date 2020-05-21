<?php 

function formatTime($item) {
    #$date = new DateTime();
    #$date->add(new DateInterval($item->sellingStatus->timeLeft));
    #$formatted = $date->format('Y-m-d H:i:s');
    //$formatted = $date->format('H:i:s');
    $formattedInterval = (new DateInterval($item->sellingStatus->timeLeft))->format('%d days %h hours %m minutes %s seconds left');
    return $formattedInterval;
}

function writeItemData($conn, $item, $itemDescription) {
    $sql = "EXEC [dbo].[InsertItemData] 
                                    @title = ?,
                                    @description = ?,
                                    @ebayId	= ?,
                                    @url = ?,
                                    @imageUrl = ?,
                                    @seller	= ?,
                                    @sellerScore = ?,
                                    @condition = ?,
                                    @itemType = ?,
                                    @auctionPrice = ?,
                                    @bidDuration = ?,
                                    @buyNowPrice = ?";
    
    $params = array($item->title,
                    $itemDescription, 
                    $item->itemId,
                    $item->viewItemURL, 
                    $item->galleryURL,
                    $item->sellerInfo->sellerUserName,
                    $item->sellerInfo->feedbackScore,
                    $item->condition->conditionDisplayName, 
                    $item->listingInfo->listingType, 
                    $item->sellingStatus->currentPrice->value, 
                    formatTime($item),
                    $item->listingInfo->buyItNowPrice->value
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