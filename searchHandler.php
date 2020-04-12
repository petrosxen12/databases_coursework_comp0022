<?php

$auctst = isset($_GET["auction"]);
$bnst = isset($_GET["buynow"]);
$blankcheckboxes = false;

if (($auctst || $bnst) && isset($_GET["searchstring"])) {
    $blankcheckboxes = true;
} else {
    $blankcheckboxes = false;
}

function showLabels($blankcheckboxes, $auctst, $bnst, $numberofitems)
{
    if ($blankcheckboxes) {

        //Database call 

        // Deal items ==> Labels with charts
        include_once("price-graph-comp.php");

        $badge = addBadge($auctst, $bnst);

        //Sanitize input first
        $searchstr = filter_var($_GET["searchstring"], FILTER_SANITIZE_STRING);

        $imgofitem = "";
        $itemdescription = " ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud e";
        $updatedtime = 15;

        for ($i = 0; $i < $numberofitems; $i++) {
            echo <<<"EOT"
                <div id="productcard" class="card mb-3" style="max-width: 70%;">
                    <div class="row no-gutters">
                        <div class="col-md-4 stretched-link">
                            <a href="#gotoitem" ><img src="$imgofitem" class="card-img" alt="..."></a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"> $searchstr $badge <a onclick=' 
                                var x = document.getElementById("unTrackedItem$i");
                                var y = document.getElementById("trackItem$i");

                                if (x || y) {
                                    if (x.style.display === "none") {
                                        x.style.display = "inline-block";
                                        y.style.display = "none";
                                        } else {
                                            x.style.display = "none";
                                            y.style.display = "inline-block"; 
                                        }
                                }' 
                                style="padding-left: 2rem;" href="#testing" class="card-link"><i id="unTrackedItem$i" class="far fa-star"></i><i id="trackItem$i" style="display:none;" class="fas fa-star"></i></a> </h5>
                                <p class="card-text">$itemdescription</p>
                                <p class="card-text"><small class="text-muted">Last updated $updatedtime mins ago</small></p>
                            </div>
                        </div>
                    </div>
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
    $auctionbadge = '<a href="&auctiononly" class="badge badge-info">Auction</a> ';

    $buynowbadge = '<a href="$buynowonly" class="badge badge-primary">Buy Now</a> ';

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
