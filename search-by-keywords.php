<?php

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;


function createFindingService() {
    // Create the service object
    global $config;
    $service = new Services\FindingService([
        'credentials' => $config['production']['credentials'],
        'globalId'    => Constants\GlobalIds::GB,
        'authToken'   => $config['production']['authToken'],
        'siteId'      => Constants\SiteIds::GB,
        'httpOptions' => [
            'verify' => false
        ] 
    ]);
    return $service;
}

function createItemFilter($types) {
    $item_filter = new Types\ItemFilter();
    $item_filter->name = 'ListingType';
    for ($i = 0; $i < count($types); $i++) {
        $item_filter->value[$i] = $types[$i];
    }
    return $item_filter;
}

function createFindingRequest($keywords, $itemFilter, $sortOrder) {
    // Create the request object
    $request = new Types\FindItemsAdvancedRequest();
    // Assign the keywords
    $request->keywords = $keywords;
    $request->itemFilter[] = $itemFilter;
    $request->sortOrder = $sortOrder;
    $request->categoryId = ["9355"];
    $request->outputSelector = ['SellerInfo', 'StoreInfo'];
    $request->paginationInput = new Types\PaginationInput();
    $request->paginationInput->entriesPerPage = 10;
    $request->paginationInput->pageNumber = 1;
    
    return $request;
}

function getFindingResponse($service, $request) {
    // Send the request
    $response = $service->findItemsAdvanced($request);

    // Output the result of the search
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
            $response = $service->findItemsAdvanced($request);

            foreach ($response->searchResult->item as $item) {
                $date = new DateTime();
                $date->add(new DateInterval($item->sellingStatus->timeLeft));
                printf(
                    "(%s) [%s] %s: %s %.2f == %s + %d\n",
                    $item->itemId,
                    $date->format('Y-m-d H:i:s'),
                    $item->title,
                    $item->sellingStatus->currentPrice->currencyId,
                    $item->sellingStatus->currentPrice->value,
                    $item->sellerInfo->sellerUserName,
                    $item->sellerInfo->feedbackScore
                );
            }
        }
    }
    return $response;
}

?>