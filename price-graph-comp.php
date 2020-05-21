<?php
function deal_card($chartid = "myChart", $title, $desc, $imgofitem, $url, $ebayID, $seller, $sellerscorebd, $auctionprice, $ending)
{
    $html = <<<"EOT"
    <div id="productcard" class="card mb-3" style="max-width: 80%;">
    <div class="card-header">
    <h4><span class="badge badge-pill badge-success">Good Deal</span></h4>
    </div>
        <div class="row no-gutters">
            <div class="col-md-4 stretched-link">
                <a href="$url" ><img style="width:80%;" src="$imgofitem" class="mx-auto card-img" alt="..."></a>
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"> $title<a onclick="trackItem($ebayID)"
                    style="padding-left: 1rem;" href="#trackitem" class="card-link"><i id="unTrackedItem$ebayID" class="far fa-star"></i><i id="trackedItem$ebayID" style="display:none;" class="fas fa-star"></i></a> </h5>
                    <p class="card-text">$desc</p>
                    <div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item font-weight-bold">Seller: $seller </li>
                        <!-- <li class="list-group-item font-weight-bold">Seller Score: $sellerscorebd </li> -->
                        <li class="list-group-item font-weight-bold">Price: £<strong>$auctionprice</strong></li>
                    </ul>
                    </div>
                    <a class="btn btn-primary" href="$url" role="button" target="_blank">More Info</a>
                </div>                            
            </div>
        </div>
        <div style="padding:0.5rem;" id="trackitemnotification$ebayID"></div>
        <div class="card-footer"><small class="text-muted">Ending in: $ending</small></div>
        </div>
    EOT;

    echo $html;
}


function dealCards()
{
    echo <<<"EOT"
<script>
    var ctx1 = document.getElementById('myChart1').getContext('2d');
    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var ctx3 = document.getElementById('myChart3').getContext('2d');

    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Price',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var chartother = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Price',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var chart = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Price',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

EOT;


    $deal1 = deal_card("myChart1", "Deal 1", "Description one", "src/assets/img/error-404-monochrome.svg");
    $deal2 = deal_card("myChart2", "Deal 2", "Description two", "src/assets/img/error-404-monochrome.svg");
    $deal3 = deal_card("myChart3", "Deal 3", "Description three", "src/assets/img/error-404-monochrome.svg");

    $dealcards =
        sprintf('<div class="container">
        <div clas="row">
            <div class="card-group">
            %s
            %s
            %s
        </div>
    </div>
    </div>', $deal1, $deal2, $deal3);
    echo $dealcards;
}
