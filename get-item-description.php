<?php 

use \DTS\eBaySDK\Shopping\Services;
use \DTS\eBaySDK\Shopping\Types;
use \DTS\eBaySDK\Shopping\Enums;

function getItemDescription($itemId) {
    global $config;
    $service = new Services\ShoppingService([
        'credentials' => $config['production']['credentials'],
        'authToken'   => $config['production']['authToken'],
        'httpOptions' => [
            'verify' => false
        ] 
    ]);
    
    $request = new Types\GetSingleItemRequestType();
    $request->ItemID = $itemId;
    $request->IncludeSelector = 'TextDescription';
    
    $response = $service->getSingleItem($request);
    
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
        $item = $response->Item;

        if (isset($item->Description)) {
            return $item->Description;
        }
    }
}

?>