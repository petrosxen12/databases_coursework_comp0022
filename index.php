<head>
  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

  <link rel="stylesheet" href="css/styles.css?v=1.0">

</head>

<body>
  <div style="border:1px solid black;">
<?php
  echo "php sucks.";
  // include "dbConnect.php";
  while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
    echo '<h3>'. $row['LastName'] .'</h3>';
  }  
?>

</div>
   <h1>hello world.</h1>
  <script src="js/scripts.js"></script>
</body>
</html>