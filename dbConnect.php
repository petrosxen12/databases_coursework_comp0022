<?php
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
