<?php
include("trackItemHandler.php");
include("dbConnect.php");
include("searchItemReturn.php");
include("price-graph-comp.php");
include("add-tracked-item.php");

$auctst = isset($_GET["auction"]);
$bnst = isset($_GET["buynow"]);
$blankcheckboxes = false;

if (($auctst || $bnst) && isset($_GET["searchstring"])) {
    $blankcheckboxes = true;
} else {
    $blankcheckboxes = false;
}


//Responsible for tracking each item based on EBAYID
//TODO: Must save each item to tracked items
if (isset($_GET['trackitem'])) {
    $ebid = $_GET['trackitem'];
    $conn = connectToDB();
    session_start();
    $accountID = $_SESSION['id'];
    if (addTrackedItem($conn, $ebid, $accountID, 1)) {
        showSuccessPopUp();
    }
}
if (isset($_GET['untrackitem'])) {
    showUntrackPopUp();
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
            $type = "BuyNow";
        }
        //Database connection
        $conn = connectToDB();
        $stmt = searchItem($conn, $searchstr, $type);

        // Deal items ==> Labels with charts

        $badge = addBadge($auctst, $bnst);

        $updatedtime = 15;

        if (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) == null) {
            echo <<<"EOT"
            <div style="max-width:70%;" class="alert alert-danger" role="alert">
                No results found.
            </div>
            EOT;
        } else {
            dealCards();
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ebayID = $row['EbayID'];
            $title = $row['Title'];
            $imgofitem = $row['ImageURL'];
            $itemdescription = $row['Description'];
            $url = $row['URL'];
            $seller = $row['Seller'];
            $sellerscore = $row['SellerScore'];

            $sellerscorebd = sellerScoreBadge($sellerscore);

            $auctionprice = $row['AuctionPrice'];

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
                                <p class="card-text"><small class="text-muted">Last updated $updatedtime mins ago</small></p>
                                
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item font-weight-bold">Seller: $seller </li>
                                    <li class="list-group-item font-weight-bold">Seller Score: $sellerscorebd </li>
                                    <li class="list-group-item font-weight-bold">Price: $auctionprice</li>
                                 </ul>

                                <a class="btn btn-primary" href="$url" role="button" target="_blank">More Info</a>
                            </div>
                            
                        </div>
                    </div>
                    <div style="padding:0.5rem;" id="trackitemnotification$ebayID"></div>
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
