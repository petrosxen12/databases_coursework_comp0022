<?php

// include("manage-tracked-items.php");
include("dbConnect.php");

// if (isset($_GET['untrackitem'])) {
//   $ebid = $_GET['untrackitem'];
//   $conn = connectToDB();
//   session_start();
//   $accountID = $_SESSION['id'];
//   if (removeTrackedItem($conn, $ebid, $accountID)) {
//     // echo "done";
//     // sqlsrv_close($conn);
//     echo "removed";
//   }
// }

function trackItemsForAccount($conn, $accountID)
{

  $sql = "SELECT * FROM Item WHERE ItemID IN (SELECT ItemID FROM TrackedItems WHERE AccountID=?)";
  $params = array(
    $accountID
  );
  $stmt = sqlsrv_prepare($conn, $sql, $params);
  if ($stmt) {
    // echo "Statement prepared.\n";
  } else {
    // echo "Error in preparing statement.\n";
    die(print_r(sqlsrv_errors(), true));
  }

  if (sqlsrv_execute($stmt)) {
    // echo "Statement executed.\n";
    return $stmt;
  } else {
    // echo "Error in executing statement.\n";
    die(print_r(sqlsrv_errors(), true));
  }

  // sqlsrv_free_stmt($stmt);
  // sqlsrv_close($conn);
}

function showModal($itemID, $description, $title)
{
  echo <<<"EOT"
  <div class="modal fade" id="modalItem$itemID" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">$title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <p>$description</p>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
      </div>
  </div>
  </div>
  EOT;
}

function showTrackedItems()
{
  // session_start();
  $accountID = $_SESSION['id'];

  $conn = connectToDB();
  $stmt = trackItemsForAccount($conn, $accountID);

  // Deal items ==> Labels with charts

  $updatedtime = 15;

  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row == null) {
      echo <<<"EOT"
        <div style="max-width:70%;" class="alert alert-danger" role="alert">
            You are currently not tracking any items. Perform a search and press the star icon to save an item.
        </div>
        EOT;
    }

    $ebayID = $row['EbayID'];
    $title = $row['Title'];
    $imgofitem = $row['ImageURL'];
    $itemdescription = $row['Description'];
    $url = $row['URL'];
    // $seller = $row['Seller'];
    // $sellerscore = $row['SellerScore'];
    $price = $row['AuctionPrice'];
    $endingtm = $row['BidDuration'];
    $type = auctionEndingCalc($endingtm);
    // $sellerscorebd = sellerScoreBadge($sellerscore);
    // $auctionprice = $row['AuctionPrice'];

    trackItemCard($title, $itemdescription, $imgofitem, $type, $ebayID, $price);
  }
}

function trackItemCard($title, $description, $image, $ending, $itemID, $price)
{
  $badge = addBadge(true, false);
  $footermsg = "Ending Soon";
  $footer =  '<small class="text-muted"><strong>' . $footermsg . ' | </strong> Last updated 3 mins ago</small>';
  $type = "primary";
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

  $modal = showModal($itemID, $description, $title);

  echo <<<"EOT"
  <div class="card border-$type">
  <img src="$image" class="rounded mx-auto d-block card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title text-$type">$title</h5>
        <p class="card-text">
        <h5>Item Price: $price</h5>
        </p>

        <!-- Button trigger modal with target being modalItem + itemID -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalItem$itemID">
        Show Details
        </button>
        <button type="button" class="btn btn-danger" onclick="removeItem($itemID)">
        Remove Item
        </button>

        <!-- Modal with item ID-->
        $modal

    </div>

    <!-- Type of sale -->
    <div class="container">
        <h5>Type $badge</h5>
    </div>

    <div class="card-footer">
        $footer
    </div>

  </div>
EOT;
}


function auctionEndingCalc($endingtime)
{
  preg_match('/(.*) days (.*) hours (.*) minutes (.*) seconds/', $endingtime, $output_array);
  echo ($output_array);
  if ($output_array[1] == 0 && $output_array[2] == 0 && $output_array[3] == 0 && $output_array[4] == 0) {
    return "ended";
  }

  if ($output_array[1] <= 1) {
    return "soon";
  }
  if ($output_array[1] > 2) {
    return "active";
  }
}
