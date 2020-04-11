<?php

/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;


function createTradingService() {
    global $config;
    $service = new Services\TradingService([
        'credentials' => $config['production']['credentials'],
        'siteId'      => Constants\SiteIds::GB,
        'httpOptions' => [
            'verify' => false
        ] 
    ]);
    return $service;
}

function createGetAllBiddersRequest($itemId) {
    global $config;
    $request = new Types\GetAllBiddersRequestType();
    $request->ItemID = $itemId;
    $request->CallMode = "ViewAll";
    $request->IncludeBiddingSummary = True;
    // An user token is required when using the Trading service.
    $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
    $request->RequesterCredentials->eBayAuthToken = $config['production']['authToken'];
    return $request;
}

function getBiddingHistory($itemId) {
    // Create the service object
    $service = createService();
    // Create the request object
    $request = createRequest($itemId);
    // Send the request.
    $response = $service->getAllBidders($request);

    /**
     * Output the result of calling the service operation.
     */
    if (isset($response->Errors)) {
        foreach ($response->Errors as $error) {
            printf(
                "%s: %s\n%s\n\n",
                $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                $error->ShortMessage,
                $error->LongMessage
            );
        }
    }

    if ($response->Ack !== 'Failure') {
        printf("Bids History for item %s:\n\n", $itemId);
        printf("UserID (Rating) --- Bid Amount --- Bid Time\n");
        foreach ($response->BidArray->Offer as $bid) {
            printf("%s (%s) --- %d %s --- %s\n", 
                $bid->User->UserID, 
                $bid->User->FeedbackScore,
                $bid->ConvertedPrice->value,
                $bid->ConvertedPrice->currencyID,
                $bid->TimeBid->format('H:i (\G\M\T) \o\n l jS F Y'));
        }
    }
}

?>