<?php

//GAZI MAIN
// $serverName = "10.30.130.20"; 
// sqlsrv_configure('WarningsReturnAsErrors',0);
// $connectionInfo = array( "Database"=>"ZABDB", "UID"=>"hcmapp", "PWD"=>"HCM@pp0621","CharacterSet" =>"UTF-8");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);


//TEST
// $serverName = "10.30.130.50"; 
// sqlsrv_configure('WarningsReturnAsErrors',0);
// $connectionInfo = array( "Database"=>"ZABDB", "UID"=>"sa", "PWD"=>"sqlgazi@s3rv3r","CharacterSet" =>"UTF-8");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

//OFFICE Test

$serverName = "."; 
sqlsrv_configure('WarningsReturnAsErrors',0);
$connectionInfo = array( "Database"=>"ZABDB", "UID"=>"sa", "PWD"=>"sql@s3rv3r","CharacterSet" =>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     // echo "Connection establish.";
}else{
     echo "Connection could not be established.";
     die( print_r( sqlsrv_errors(), true));
}


?>
