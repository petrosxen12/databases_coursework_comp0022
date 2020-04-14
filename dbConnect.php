    <?php
    // PHP Data Objects(PDO) Sample Code:
    // try {
    //     $conn = new PDO("sqlsrv:server = tcp:dbserverxen.database.windows.net,1433; Database = Ebay", "xen", "malakas123!@");
    //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // } catch (PDOException $e) {
    //     print("Error connecting to SQL Server.");
    //     die(print_r($e));
    // }

    // // SQL Server Extension Sample Code:
    // $connectionInfo = array("UID" => "xen", "pwd" => "malakas123!@", "Database" => "Ebay", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    // $serverName = "tcp:dbserverxen.database.windows.net,1433";
    // $conn = sqlsrv_connect($serverName, $connectionInfo);

    // if ($conn) {
    //     // print("Connected succesfully.");
    // }

    function connectToDB()
    {
        try {
            $serverName = "tcp:dbserverxen.database.windows.net,1433";
            $connectionOptions = array("UID" => "xen", "PWD" => "malakas123!@", "Database" => "Ebay", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
            $conn = sqlsrv_connect($serverName, $connectionOptions);
            if ($conn == false)
                die(FormatErrors(sqlsrv_errors()));
            else {
                echo ("Connection Successful!\n");
            }
        } catch (Exception $e) {
            echo ("Error!");
        }
        return $conn;
    }

    ?>