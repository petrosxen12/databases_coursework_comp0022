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

/**
 * Include the SDK by using the autoloader from Composer.
 */
require 'C:\composer\vendor\autoload.php';

/**
 * Include the configuration values.
 *
 * Ensure that you have edited the configuration.php file
 * to include your application keys.
 */
$config = require __DIR__.'\configuration.php';

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

/**
 * Create the service object.
 */
$service = new Services\FindingService([
    'credentials' => $config['sandbox']['credentials'],
    'globalId'    => Constants\GlobalIds::US,
    'authToken'   => $config['sandbox']['authToken'],
    'siteId'      => Constants\SiteIds::US,
    'httpOptions' => [
        'verify' => false
    ] 
]);

/**
 * Create the request object.
 */
$request = new Types\FindItemsByKeywordsRequest();
$item_filter = new Types\ItemFilter();
$item_filter->name = 'ListingType';
$item_filter->value[] = 'Auction';
//$listing_type->value[] = 'AuctionWithBIN';
$request->itemFilter[] = $item_filter;

/**
 * Assign the keywords.
 */
$request->keywords = 'camera';
$request->paginationInput = new Types\PaginationInput();
$request->paginationInput->entriesPerPage = 10;
$request->paginationInput->pageNumber = 1;
$request->sortOrder = 'EndTimeSoonest';

/**
 * Send the request.
 */
$response = $service->findItemsByKeywords($request);


/**
 * Output the result of the search.
 */
if (isset($response->errorMessage)) {
    foreach ($response->errorMessage->error as $error) {
        printf(
            "%s: %s\n\n",
            $error->severity=== Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
            $error->message
        );
    }
}

if ($response->ack !== 'Failure') {
    //print($response->searchResult->item[0]);
    //for ($page = 1; $page <= $response->paginationOutput->totalPages; $page++) {
    for ($page = 1; $page <= 5; $page++) {

        $request->paginationInput->pageNumber = $page;
        $response = $service->findItemsByKeywords($request);

        foreach ($response->searchResult->item as $item) {
            $date = new DateTime();
            $date->add(new DateInterval($item->sellingStatus->timeLeft));
            printf(
                "(%s) [%s] %s: %s %.2f\n",
                $item->itemId,
                $date->format('Y-m-d H:i:s'),
                $item->title,
                $item->sellingStatus->currentPrice->currencyId,
                $item->sellingStatus->currentPrice->value
            );
        }
    }
}
