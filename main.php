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
include "search-by-keywords.php";
include "connect-to-database.php";
include "get-item-description.php";
include "insert-item-data.php";
include "get-item-bidding-history.php";
include "insert-bidding-data.php";


$searchResults = getSearchResults(["AuctionWithBIN"], "samsung s10", "BestMatch");

if ($searchResults) {
    $dbConnection = connectToDB();
    foreach($searchResults->searchResult->item as $item) {
        $description = getItemDescription($item->itemId);
        writeItemData($dbConnection, $item, $description);
        echo($item->itemId);
        $biddingResults = getBiddingResults($item->itemId);
        if ($biddingResults) {
            foreach($biddingResults->BidArray->Offer as $bid) {
                writeBiddingData($dbConnection, 
                                 $item->itemId, 
                                 $bid->User->UserID, 
                                 $bid->ConvertedPrice->value,
                                 $bid->TimeBid->format('Y-m-d H:i:s'));
            }
        }
    }
}
/*Free the statement and connection resources. */
sqlsrv_close($dbConnection);
?>