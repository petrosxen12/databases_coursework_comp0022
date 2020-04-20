<?php
// include "dbConnect.php";

// session_start();
// $accountid = $_SESSION['id'];

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



function getPrevDeals($conn, $accountid)
{

    $sql = "SELECT sh.SearchID,itm.*, dl.ItemID,sh.SearchString,SearchTime FROM SearchHistory as sh, deals as dl, Item as itm WHERE dl.SearchID = sh.SearchID AND sh.AccountID=$accountid AND itm.ItemID
    IN (SELECT dl.ItemID FROM Item WHERE AccountID=$accountid)";

    // $conn = connectToDB();
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $counter = 1;
    $prevsearchid = 0;
    $date = 0;

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $currsearchid = $row['SearchID'];
        $st = $row['SearchTime'];
        $title = $row['Title'];
        $image = $row['ImageURL'];
        $price = $row['AuctionPrice'];
        $etp = $row['BidDuration'];
        $url = $row['URL'];

        if ($prevsearchid == 0 && $date == 0) {
            echo '<div class="card-deck">';
            $prevsearchid = $currsearchid;
            $date = date_format($st, 'Y-m-d H:i:s');
        }

        if ($currsearchid != $prevsearchid) {
            $prevsearchid = $currsearchid;
            $date = date_format($st, 'Y-m-d H:i');
            echo '</div>';
            echo '<div class="card-deck">';
            // echo "\n\n";
        }
        // echo "$counter " . $row['SearchID'] . " " . $row['Title'] . "\n";
        // $counter++;


        $endtype = endcalc($etp);
        prevSearchCard($image, $title, $price, $date, $endtype, $url);
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}

function prevSearchCard($image, $title, $price, $date, $ending, $url)
{
    $badge = addBadge(true, false);
    if ($ending == "soon") {
        $type = "warning";
        $footermsg = "Ending Soon";
    }
    if ($ending == "ended") {
        $type = "danger";
        $footermsg = "Auction Ended";
    }
    if ($ending == "active") {
        $type = "success";
        $footermsg = "Active";
    }

    echo <<<"EOT"
    <div class="card border-$type" style="max-width:60%">
    <img src="$image"  style="padding-top:0.3rem;" class="rounded mx-auto d-block card-img-top" alt="...">
      <div class="card-body">
          <h5 class="card-title">$title</h5>
          <p class="card-text">
          <p>Item Price: £<strong>$price</strong></p>
          <p>Type: $badge</p>
          </p>
          <a class="btn btn-primary" href="$url" role="button" target="_blank">More Info</a>
      </div>
  
      <div class="card-footer">
            <p><strong>$footermsg</strong></p>
            <p>Searched at <strong>$date</strong></p>
      </div>
  
    </div>

    EOT;
}


function endcalc($endingtime)
{
    preg_match('/(.*) days (.*) hours (.*) minutes (.*) seconds/', $endingtime, $output_array);
    // echo ($output_array);
    if ($output_array[1] == 0 && $output_array[2] == 0 && $output_array[3] == 0 && $output_array[4] == 0) {
        return "ended";
    }

    if ($output_array[1] <= 1) {
        return "soon";
    }
    if ($output_array[1] > 1) {
        return "active";
    }
}
// getPrevDeals(2);
