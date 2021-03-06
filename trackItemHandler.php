<?php

// include("manage-tracked-items.php");
include("dbConnect.php");
include("sendmail.php");
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

  $flag = 0;

  $counter = 1;
  $emailarray = array();

  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    if ($row == null) {
      echo <<<"EOT"
      <div class="container">
      <div style="max-width:70%;" class="alert alert-danger" role="alert">
          You are currently not tracking any items. Perform a search and press the star icon to save an item.
      </div>
      </div>
      EOT;
      return;
    }

    $ebayID = $row['EbayID'];
    $title = $row['Title'];
    $imgofitem = $row['ImageURL'];
    $itemdescription = $row['Description'];
    $url = $row['URL'];
    $sellingtype = $row['ItemType'];
    // $seller = $row['Seller'];
    // $sellerscore = $row['SellerScore'];
    $auctionprice = $row['AuctionPrice'];
    $buynowprice = $row['BuyNowPrice'];
    $endingtm = $row['BidDuration'];
    $endingtype = auctionEndingCalc($endingtm);
    // $sellerscorebd = sellerScoreBadge($sellerscore);
    // $auctionprice = $row['AuctionPrice'];

    if ($counter < 4 && $row != null) {
      array_push($emailarray, $row);
      $counter++;
    }

    trackItemCard($title, $itemdescription, $imgofitem, $endingtype, $ebayID, $auctionprice, $buynowprice, $sellingtype, $url);
  }

  if ($counter == 4) {
    $mail = $_SESSION['email'];
    // echo $mail;  
    // echo count($emailarray);
    sendThreeTrackedItems(
      $mail,
      $emailarray[0]['Title'],
      $emailarray[0]['ImageURL'],
      $emailarray[0]['Description'],
      $emailarray[1]['Title'],
      $emailarray[1]['ImageURL'],
      $emailarray[1]['Description'],
      $emailarray[2]['Title'],
      $emailarray[2]['ImageURL'],
      $emailarray[2]['Description']
    );
  }
}

function trackItemCard($title, $description, $image, $ending, $itemID, $auctionprice, $buynowprice, $sellingtype, $url)
{
  if ($sellingtype == "Auction") {
    $badge = addBadge(true, false);
  }
  if ($sellingtype == "AuctionWithBIN") {
    $badge = addBadge(true, true);
  }
  if ($sellingtype == "FixedPrice") {
    $badge = addBadge(false, true);
  }

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
  $footer =  '<small class="text-muted"><strong>' . $footermsg . ' | </strong> Last updated 3 mins ago</small>';


  $modal = showModal($itemID, $description, $title);

  if ($sellingtype == "Auction") {
    echo <<<"EOT"
    <div class="card border-$type">
    <img src="$image"  style="padding-top:0.3rem;" class="rounded mx-auto d-block card-img-top" alt="...">
      <div class="card-body">
          <h5 class="card-title text-$type">$title</h5>
          <p class="card-text">
          <h5>Item Price: £<strong>$auctionprice</strong></h5>
          </p>
  
          <!-- Button trigger modal with target being modalItem + itemID -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalItem$itemID">
          Show Details
          </button>
          <button type="button" class="btn btn-danger" onclick="removeItem($itemID)">
          Remove Item
          </button>
          <a role="button" class="btn btn-success" href="$url" target="_blank">
          Go to item
          </a>
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
  if ($sellingtype == "AuctionWithBIN") {
    echo <<<"EOT"
    <div class="card border-$type">
    <img src="$image"  style="padding-top:0.3rem;" class="rounded mx-auto d-block card-img-top" alt="...">
      <div class="card-body">
          <h5 class="card-title text-$type">$title</h5>
          <p class="card-text">
          <h5>Item Price: £<strong>$auctionprice</strong></h5>
          <h5>Buy Now Item Price: £<strong>$buynowprice</strong></h5>
          </p>
  
          <!-- Button trigger modal with target being modalItem + itemID -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalItem$itemID">
          Show Details
          </button>
          <button type="button" class="btn btn-danger" onclick="removeItem($itemID)">
          Remove Item
          </button>
          <a role="button" class="btn btn-success" href="$url" target="_blank">
          Go to item
          </a>
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
  if ($sellingtype == "FixedPrice") {
    echo <<<"EOT"
    <div class="card border-$type">
    <img src="$image"  style="padding-top:0.3rem;" class="rounded mx-auto d-block card-img-top" alt="...">
      <div class="card-body">
          <h5 class="card-title text-$type">$title</h5>
          <p class="card-text">
          <h5>Item Price: £<strong>$auctionprice</strong></h5>
          </p>
  
          <!-- Button trigger modal with target being modalItem + itemID -->
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalItem$itemID">
          Show Details
          </button>
          <button type="button" class="btn btn-danger" onclick="removeItem($itemID)">
          Remove Item
          </button>
          <a role="button" class="btn btn-success" href="$url" target="_blank">
          Go to item
          </a>
          <!-- Modal with item ID-->
          $modal
  
      </div>
  
      <!-- Type of sale -->
      <div class="container">
          <h5>Type $badge</h5>
      </div>
  
      <div class="card-footer">
          <!-- $footer -->
      </div>
  
    </div>
  EOT;
  }
}


function auctionEndingCalc($endingtime)
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
