<?php 
include "connect-to-database.php";

function splitPhrase($phrase) {
    $keywords = explode(" ", $phrase);
    $newPhrase = join(" and ", $keywords);
    return $newPhrase;
}

function searchItem($conn, $phrase, $type) {
    $keyword = splitPhrase($phrase);
    $sql = "EXEC [dbo].[SearchItemByKeyword] @keyword = ?, @type = ?";
    //$output = [];
    $params = array(
        $keyword,
        $type
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

    /* Retrieve each row as an associative array and display the results.*/  
    while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))  
    {  
        echo $row['Title']."\n";  
    }   
    sqlsrv_free_stmt($stmt);
}

$connection = connectToDB();
searchItem($connection, "samsung s8 midnight", "AuctionWithBIN");

/*Free the statement and connection resources. */
sqlsrv_close($connection);
?>