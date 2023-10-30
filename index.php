<?php


//GAZI MAIN

// $host = '10.30.130.20'; // Replace with your server name or IP address
// $dbname = 'ZABDB'; // Replace with your database name
// $username = 'hcmapp'; // Replace with your database username
// $password = 'HCM@pp0621'; // Replace with your database password

//GAZI TEST

// $host = '10.30.130.50'; // Replace with your server name or IP address
// $dbname = 'ZABDB'; // Replace with your database name
// $username = 'sa'; // Replace with your database username
// $password = 'sqlgazi@s3rv3r'; // Replace with your database password

//OFFICE Test Real

$host = '.'; // Replace with your server name or IP address
$dbname = 'ZABDB'; // Replace with your database name
$username = 'sa'; // Replace with your database username
$password = 'sql@s3rv3r'; // Replace with your database password

$dsn = "sqlsrv:Server=$host;Database=$dbname";
$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);

try {
    $conn = new PDO($dsn, $username, $password, $options);
    //echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
