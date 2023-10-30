<?php echo phpinfo(); ?>

<?php
$serverName = "TEST-MOBILE-APP"; 
sqlsrv_configure('WarningsReturnAsErrors',0);
$connectionInfo = array( "Database"=>"Aygaz_Sales", "UID"=>"sa", "PWD"=>"sql@s3rv3r");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn ) {
     //echo "Connection establish.";
}else{
     echo "Connection could not be established.";
     die( print_r( sqlsrv_errors(), true));
}

?>
