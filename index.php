<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <meta charset="utf-8">

  <title>The HTML5 Herald</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">

</head>

<body>
  <div style="border:1px solid black;">

<table class="table">
  <thead class="black white-text">
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
    </tr>
  </thead>
  <tbody>
  <tr>
  <?php
  $counter=0;
  echo "php sucks.";
  include "dbConnect.php";
  while($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)){
    // echo '<h3>'. $row['LastName'] .$counter++.'</h3>';
    echo '<th scope="row">'.$counter++.'</th>
    <td>'.$row['FirstName'].'</td>
    <td>'.$row['LastName'].'</td>';

  }  

  sqlsrv_free_stmt($getResults);  
  ?>
  </tr>
  </tbody>
</table>

</div>
   <h1>hello world.</h1>
  <script src="js/scripts.js"></script>
</body>
</html>
