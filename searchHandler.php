<?php
// include("trackItemHandler.php");
session_start();

include("dbConnect.php");
include("search-item-by-dealiness.php");
include("price-graph-comp.php");
include("manage-tracked-items.php");

$auctst = isset($_GET["auction"]);
$bnst = isset($_GET["buynow"]);
$blankcheckboxes = false;

if (($auctst || $bnst) && isset($_GET["searchstring"])) {
    $blankcheckboxes = true;
} else {
    $blankcheckboxes = false;
}

function showSuccessPopUp()
{
    $tr = isset($_GET['trackitem']);
    if ($tr) {
        echo <<<"EOT"
        <div class="alert alert-success" role="alert">
        <strong>Item Saved</strong> You can check its progress in the tracked items section
      </div>
    EOT;
    }
}


function showUntrackPopUp()
{
    echo <<<"EOT"
        <div class="alert alert-danger" role="alert">
        <strong>Item unsaved</strong> Removed from tracked items
      </div>
    EOT;
}

//Responsible for tracking each item based on EBAYID
//DONE: Must save each item to tracked items
if (isset($_GET['trackitem'])) {
    $ebid = $_GET['trackitem'];
    $conn = connectToDB();
    // session_start();
    $accountID = $_SESSION['id'];
    if (addTrackedItem($conn, $ebid, $accountID, 1)) {
        showSuccessPopUp();
    }
}

//DONE: Remove each item from tracked when unstarred 
if (isset($_GET['untrackitem'])) {
    $ebid = $_GET['untrackitem'];
    $conn = connectToDB();
    // session_start();
    $accountID = $_SESSION['id'];
    if (removeTrackedItem($conn, $ebid, $accountID)) {
        showUntrackPopUp();
    }
}

if (isset($_GET['untrackitemti'])) {
    $ebid = $_GET['untrackitemti'];
    $conn = connectToDB();
    session_start();
    $accountID = $_SESSION['id'];
    if (removeTrackedItem($conn, $ebid, $accountID)) {
        "removed";
    }
}

function showLabels($blankcheckboxes, $auctst, $bnst)
{
    if ($blankcheckboxes) {


        //Sanitize input first
        $searchstr = filter_var($_GET["searchstring"], FILTER_SANITIZE_STRING);

        //Return items from DB
        //TODO: Add filtration by checkbox

        if ($auctst) {
            $type = "Auction";
        }
        if ($bnst) {
            $type = "FixedPrice";
        }
        if ($auctst && $bnst) {
            $type = "AuctionWithBIN";
        }

        //Database connection
        $conn = connectToDB();
        $accountID = $_SESSION['id'];
        $data = searchItemsByDealiness($conn, splitPhrase($searchstr), $type, $accountID);

        // Deal items ==> Labels with charts

        $badge = addBadge($auctst, $bnst);

        $updatedtime = 15;

        if ($data == NULL) {
            echo <<<"EOT"
            <div style="max-width:70%;" class="alert alert-danger" role="alert">
                No results found.
            </div>
            EOT;
            return;
        }

        $counter = 0;
        foreach ($data as $item) {

            $ebayID = $item['EbayID'];
            $title = $item['Title'];
            $imgofitem = $item['ImageURL'];
            $itemdescription = $item['Description'];
            $url = $item['URL'];
            $seller = $item['Seller'];
            $sellerscore = $item['SellerScore'];
            $endingin = $item['BidDuration'];

            $sellerscorebd = sellerScoreBadge($sellerscore);

            $auctionprice = $item['AuctionPrice'];
            $output_array;
            preg_match('/(.*) days (.*) hours/', $endingin, $output_array);
            $endingformatted = $output_array[0];


            if ($counter < 3) {
                deal_card("myChart1", $title, $itemdescription, $imgofitem, $url, $ebayID, $seller, $sellerscorebd, $auctionprice, $endingformatted);
                $counter++;
                continue;
            }


            echo <<<"EOT"
                <div id="productcard" class="card mb-3" style="max-width: 80%;">
                    <div class="row no-gutters">
                        <div class="col-md-4 stretched-link">
                            <a href="$url" ><img style="width:80%;" src="$imgofitem" class="mx-auto card-img" alt="..."></a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"> $title $badge <a onclick="trackItem($ebayID)"
                                style="padding-left: 1rem;" href="#trackitem" class="card-link"><i id="unTrackedItem$ebayID" class="far fa-star"></i><i id="trackedItem$ebayID" style="display:none;" class="fas fa-star"></i></a> </h5>
                                <p class="card-text">$itemdescription</p>
                                <div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item font-weight-bold">Seller: $seller </li>
                                    <li class="list-group-item font-weight-bold">Seller Score: $sellerscorebd </li>
                                    <li class="list-group-item font-weight-bold">Price: £<strong>$auctionprice</strong></li>
                                 </ul>
                                </div>
                                <a class="btn btn-primary" href="$url" role="button" target="_blank">More Info</a>
                            </div>                            
                        </div>
                    </div>
                    <div style="padding:0.5rem;" id="trackitemnotification$ebayID"></div>
                    <div class="card-footer"><p class="font-weight-bold"> Ending in: $endingformatted</p></div>
                </div>
        EOT;
        }
    }
}

function sellerScoreBadge($sellerscore)
{
    if ($sellerscore > 1000) {
        return '<h5><span class="badge badge-pill badge-success">Excellent</span></h5>';
    }

    if ($sellerscore > 100) {
        return '<h5><span class="badge badge-pill badge-primary">Good</span></h5>';
    } else {
        return '<h5><span class="badge badge-pill badge-danger">Bad</span></h5>';
    }
}


function showErrorBadge($blankcheckboxes)
{
    if (!$blankcheckboxes) {
        echo <<<"EOT"
        <div style="" class="alert alert-primary" role="alert">
            You must choose the type of sale for the searched item.
        </div>
        EOT;
    }
}

function addBadge(bool $auction, bool $buynow)
{
    $auctionbadge = '<a href="#auctiononly" class="badge badge-info">Auction</a> ';

    $buynowbadge = '<a href="#buynowonly" class="badge badge-primary">Buy Now</a> ';

    if ($auction && $buynow) {
        return $auctionbadge . " " . $buynowbadge;
    }
    if ($auction) {
        return $auctionbadge;
    }
    if ($buynow) {
        return $buynowbadge;
    }
}
