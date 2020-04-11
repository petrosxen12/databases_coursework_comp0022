<?php

$auctst = isset($_GET["auction"]);
$bnst = isset($_GET["buynow"]);

if ($auctst || $bnst) {
    $blankcheckboxes = true;
} else {
    $blankcheckboxes = false;
}

function showLabels($blankcheckboxes, $auctst, $bnst, $numberofitems)
{
    if ($blankcheckboxes) {
        $badge = addBadge($auctst, $bnst);

        $searchstr = $_GET["searchstring"];
        $imgofitem = "";
        $itemdescription = "";

        for ($i = 0; $i < $numberofitems; $i++) {
            echo <<<"EOT"
                <div class="card mb-3" style="max-width: 80%;">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <img src="$imgofitem" class="card-img" alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title"> $searchstr $badge  </h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
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
        <div style="" class="alert alert-danger" role="alert">
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
