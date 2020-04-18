<?php
include("trackItemHandler.php");

session_start();
$deploenv = getenv('APPSETTING_env');

if (!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true) {
    $deploenv = getenv('APPSETTING_env');

    if ($deploenv == "true") {
        // echo "inside prod";
        header("location: /dist/login.php");
        exit();
    } else {
        // echo "inside else";
        header("location: dist/login.php");
        exit();
    }
}

// $accountID = $_SESSION["id"];

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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Ebay Admin Dashboard</title>
    <link href="dist/css/styles.css" rel="stylesheet" />
    <link href="dist/css/cards.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Ebay Monitoring Tool</a><button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button><!-- Navbar Search-->
        <form ction="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0" type="">
            <div class="input-group">
                <input class="form-control" name="searchstring" type="searchstring" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label style="color:white; padding-left: 1rem;"><input type="checkbox" name="auction" value="1">Auction</label>
                        <!-- <input type="hidden" name="auction" value="0"> -->
                    </div>
                    <div class="checkbox">
                        <!-- <input type="hidden" name="buynow" value="0"> -->
                        <label style="color:white; padding-left: 1rem;"><input type="checkbox" name="buynow" value="1">Buy Now</label>
                    </div>
                </div>

            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Settings</a><a class="dropdown-item" href="#">Activity Log</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href=<?php if ($deploenv == "true") {
                                                        echo "dist/logout.php";
                                                    } else {
                                                        // echo "inside else";
                                                        echo ("dist/logout.php");
                                                    } ?>>Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Search Items
                        </a><a class="nav-link" href="prevsearches.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Previous Searches
                        </a>
                        <div class="sb-sidenav-menu-heading">Tracking</div>
                        <a class="nav-link" href="trackedItems.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Tracked Items
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?php
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo $_SESSION["email"];
                    }
                    ?>

                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">

            <main>

                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <h1 class="display-4">Tracked Items</h1>
                        <p class="lead">Items you want to keep an eye on.</p>
                    </div>
                </div>

                <!-- FIXME: ITEMS BELOW MUST BE LOADED FROM DB -->
                <script>
                    function removeItem(itemNumber) {
                        var untritem = "untrackitemti";

                        if (window.XMLHttpRequest) {
                            // code for IE7+, Firefox, Chrome, Opera, Safari
                            xmlhttp = new XMLHttpRequest();
                        } else {
                            // code for IE6, IE5
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }

                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                location.reload();
                                if (this.responseText === "removed") {
                                    alert("Item Removed");
                                }
                            };
                        }
                        var sendResp = "searchHandler.php?".concat(untritem).concat("=").concat(itemNumber);
                        xmlhttp.open("GET", sendResp, true);
                        xmlhttp.send();
                    }
                </script>


                <!-- TODO: Add badge to each card that shows if BUY NOW or AUCTION -->
                <div style="padding-top: 1rem;" class="container">
                    <div class="card-deck">

                        <?php showTrackedItems(); ?>

                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2019</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="dist/js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="dist/assets/demo/chart-area-demo.js"></script>
    <script src="dist/assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="dist/assets/demo/datatables-demo.js"></script>
</body>

</html>