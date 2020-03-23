    /*
    $host = 'ebaytool.mysql.database.azure.com';
    $username = 'xen@ebaytool';
    $pass = 'malakas123!@';
    $db_name = 'users';

    //Establishes the connection
    $conn = mysqli_init();
    mysqli_real_connect($conn, $host, $username, $pass, $db_name, 3306);
    if (mysqli_connect_errno($conn)) {
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    */
    <?php
    // PHP Data Objects(PDO) Sample Code:
    try {
        $conn = new PDO("sqlsrv:server = tcp:dbserverxen.database.windows.net,1433; Database = Ebay", "xen", "malakas123!@");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }

    // SQL Server Extension Sample Code:
    $connectionInfo = array("UID" => "xen", "pwd" => "malakas123!@", "Database" => "Ebay", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:dbserverxen.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    ?>