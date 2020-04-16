<?php 
/**
 * Copyright 2016 David T. Sadler
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

// Include the SDK by using the autoloader from Composer
require 'C:\composer\vendor\autoload.php';
include "connect-to-database.php";
include "insert-item-data.php";
include "search-by-keywords.php";
include "get-item-bidding-history.php";
$config = require __DIR__.'\configuration.php';

$findingService = createFindingService();
$types = array("AuctionWithBIN");
$auctionFilter = createItemFilter($types);
$findingRequest = createFindingRequest("samsung galaxy", $auctionFilter, "BestMatch");
$response = getFindingResponse($findingService, $findingRequest);

if ($response->ack != "Failure") {
    $dbConnection = connectToDB();
    foreach($response->searchResult->item as $item) {
        writeDataToDB($dbConnection, $item);
    }
}
/*Free the statement and connection resources. */
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>