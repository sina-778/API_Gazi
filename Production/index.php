<?php



$serverName = "."; 
sqlsrv_configure('WarningsReturnAsErrors',0);
$connectionInfo = array( "Database"=>"ZABDB", "UID"=>"sa", "PWD"=>"SQLs3rv3r","CharacterSet" =>"UTF-8");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     // echo "Connection establish.";
}else{
     echo "Connection could not be established.";
     die( print_r( sqlsrv_errors(), true));
}

?>
