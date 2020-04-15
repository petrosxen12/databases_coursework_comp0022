<?php
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

require_once "dbConnect.php";
include "trackItemHandler.php";
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
                            Dashboard
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Layouts
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav"><a class="nav-link" href="layout-static.html">Static
                                    Navigation</a><a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Pages
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav"><a class="nav-link" href="login.html">Login</a><a class="nav-link" href="register.html">Register</a><a class="nav-link" href="password.html">Forgot Password</a></nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div></a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav"><a class="nav-link" href="401.html">401
                                            Page</a><a class="nav-link" href="404.html">404 Page</a><a class="nav-link" href="500.html">500 Page</a></nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a><a class="nav-link" href="tables.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            Tables
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

                <!-- TODO: Add badge to each card that shows if BUY NOW or AUCTION -->
                <div style="padding-top: 1rem;" class="container">
                    <div class="card-deck">
                        <div class="card border-warning">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title text-warning">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>

                                <!-- Button trigger modal with target being modalItem + itemID -->
                                <!-- Modal with item ID-->
                                <?php showModal(12, "warning"); ?>

                            </div>
                            <div class="card-footer">
                                <small class="text-muted"><strong>Ending soon | </strong> Last updated 3 mins ago</small>
                            </div>

                        </div>
                        <div class="card border-danger">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title text-danger">Card title</h5>
                                <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                                <!-- Button trigger modal with target being modalItem + itemID -->
                                <!-- Modal with item ID-->
                                <?php showModal(13, "danger"); ?>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted font-weight-bold">Sale Ended</small>
                            </div>
                        </div>
                        <div class="card">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>

                                <!-- Modal with item ID-->
                                <?php showModal(14, "primary"); ?>

                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </div>
                        </div>

                    </div>
                    <div class="card-deck">
                        <div class="card">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </div>
                        </div>
                        <div class="card">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </div>
                        </div>
                        <div class="card">
                            <img src="https://multimedia.bbycastatic.ca/multimedia/products/500x500/135/13527/13527274.jpg" class="rounded mx-auto d-block card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">Last updated 3 mins ago</small>
                            </div>
                        </div>
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