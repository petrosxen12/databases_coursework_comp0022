<?php

function splitPhrase($phrase)
{
    $keywords = explode(" ", $phrase);
    $newPhrase = join(" and ", $keywords);
    return $newPhrase;
}

function searchItemsByDealiness($conn, $keyword, $listingType, $accountId)
{
    $sql = "EXEC [dbo].[SearchItemAndCalcDeals] 
                                    @keyword = ?,
                                    @type = ?,
                                    @AccountId = ?";

    $params = array(
        $keyword,
        $listingType,
        $accountId
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

        $data = array();
        while (sqlsrv_next_result($stmt)) {
            /* Retrieve each row as an associative array and display the results.*/
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                //echo $row['Title']."\n"; 
                array_push($data, $row);
            }
        }

        if ($data) {
            return $data;
        } else {
            return NULL;
        }
    } else {
        // echo "Error in executing statement.\n";
        die(print_r(sqlsrv_errors(), true));
    }

    sqlsrv_free_stmt($stmt);
}

// $conn = connectToDB();
// $data = searchItemsByDealiness($conn, splitPhrase("huawei p20"), "Auction", "1");

// $datasize = count($data);

// for ($i = 0; $i < $datasize; $i++) {
//     echo $data[$i]['Title'] . "\n";
//     echo " ";
// }

// sqlsrv_close($conn);
