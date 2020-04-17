<?php
include("trackItemHandler.php");
include("dbConnect.php");
include("searchItemReturn.php");
include("price-graph-comp.php");

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
    // echo $_GET['trackitem'];
    showSuccessPopUp();
}
if (isset($_GET['untrackitem'])) {
    showUntrackPopUp();
}

function showLabels($blankcheckboxes, $auctst, $bnst)
{
    if ($blankcheckboxes) {

        dealCards();
        //Database call 
        $conn = connectToDB();

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

        $stmt = searchItem($conn, $searchstr, $type);

        // Deal items ==> Labels with charts

        $badge = addBadge($auctst, $bnst);


        // $imgofitem = "https://cdn10.bigcommerce.com/s-t4yqg98af9/products/401759/images/5299375/apihiyxpy__55866.1539898792.256.256.jpg?c=2";
        $updatedtime = 15;

        if (sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) == null) {
            echo <<<"EOT"
            <div style="max-width:70%;" class="alert alert-danger" role="alert">
                No results found.
            </div>
            EOT;
        }

        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $ebayID = $row['EbayID'];
            $title = $row['Title'];
            $imgofitem = $row['ImageURL'];
            $itemdescription = $row['Description'];
            $url = $row['URL'];

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
