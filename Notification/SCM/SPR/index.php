<?php

// $host = '10.30.130.50'; // Replace with your server name or IP address
// $dbname = 'ZABDB'; // Replace with your database name
// $username = 'sa'; // Replace with your database username
// $password = 'sqlgazi@s3rv3r'; // Replace with your database password


$serverName = "10.30.130.50"; 
sqlsrv_configure('WarningsReturnAsErrors',0);
$connectionInfo = array( "Database"=>"ZABDB", "UID"=>"sa", "PWD"=>"sqlgazi@s3rv3r","CharacterSet" =>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


//GAZI MAIN

// $serverName = "10.30.130.20"; 
// sqlsrv_configure('WarningsReturnAsErrors',0);
// $connectionInfo = array( "Database"=>"ZABDB", "UID"=>"hcmapp", "PWD"=>"HCM@pp0621","CharacterSet" =>"UTF-8");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     // echo "Connection establish.";
}else{
     echo "Connection could not be established.";
     die( print_r( sqlsrv_errors(), true));
}


?>
