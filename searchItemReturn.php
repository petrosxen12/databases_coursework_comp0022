<?php

function splitPhrase($phrase)
{
    $keywords = explode(" ", $phrase);
    $newPhrase = join(" and ", $keywords);
    return $newPhrase;
}

function searchItem($conn, $phrase, $type)
{
    $keyword = splitPhrase($phrase);
    $sql = "EXEC [dbo].[SearchItemByKeyword] @keyword = ?, @type = ?";
    //$output = [];
    $params = array(
        $keyword,
        $type
    );
    $stmt = sqlsrv_prepare($conn, $sql, $params);
    if ($stmt) {
        // echo "Statement prepared.\n";
    } else {
        echo "Error in preparing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_execute($stmt)) {
        // echo "Statement executed.\n";
    } else {
        echo "Error in executing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }
    // sqlsrv_close($conn);
    return $stmt;
    /* Retrieve each row as an associative array and display the results.*/
    // while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //     echo $row['Title'] . "\n";
    // }
    // return $row;
}

// $connection = connectToDB();
// searchItem($connection, "iphone");
