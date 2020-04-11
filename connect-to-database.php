<?php

function connectToDB() {
    try {
        $serverName = "tcp:dbserverxen.database.windows.net,1433";
        $connectionOptions = array("UID" => "xen", "PWD" => "malakas123!@", "Database" => "Ebay", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
        $conn = sqlsrv_connect($serverName, $connectionOptions);  
        if($conn == false)  
            die(FormatErrors(sqlsrv_errors())); 
        else {
            echo("Connection Successful!\n");
        }
    }
    catch (Exception $e) {
        echo("Error!");
    }
    return $conn;
}

?>