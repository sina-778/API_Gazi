<?php


header('content-type: application/json');

	$request = $_SERVER['REQUEST_METHOD'];


	switch ( $request) {

		case 'POST':
			// code...
			$data=json_decode(file_get_contents('php://input'),true);
			postmethod($data);
		break;

		default:
			echo '{"name" : "data not found"}';
		break;
	}


    function postmethod($data){
        include "index.php";
    
        $TSOID = $data["TSOID"];
        $InTime = $data["InTime"];
        $OutTime = $data["OutTime"];
        $xidsup = $data["xidsup"];
        $dealer = $data["dealer"];
        $Latitude = $data["Latitude"];
        $Longitude = $data["Longitude"];
        $location = $data["location"];
        $image = $data["image"];

    
        // $sql= "INSERT INTO TSOAttDealer (TSOID, xdate, InTime, xidsup, dealer, Latitude, Longitude, location, image) 
        // VALUES ('$TSOID', getdate(), '$InTime','$xidsup', '$dealer', '$Latitude', '$Longitude', '$location', '$image')";
        
        $sql = "
        IF EXISTS (SELECT * FROM TSOAttDealer WHERE TSOID='$TSOID' and CONVERT(DATE, xdate)  = CONVERT(DATE, GETDATE()) and dealer = '$dealer')

        BEGIN
        UPDATE TSOAttDealer 
            SET OutTime = '$OutTime', Latitude = '$Latitude', Longitude = '$Longitude', location = '$location'
            WHERE  TSOID='$TSOID' and  CONVERT(DATE, xdate)  = CONVERT(DATE, GETDATE()) and dealer = '$dealer'
        END
        ELSE
        BEGIN
            INSERT INTO TSOAttDealer (TSOID, xdate, InTime, xidsup, dealer, Latitude, Longitude, location, image) 
            VALUES ('$TSOID', getdate(), '$InTime','$xidsup', '$dealer', '$Latitude', '$Longitude', '$location', '$image')
        END";

        if (sqlsrv_query($conn, $sql)) {
            echo '{"result" : "Data Inserted"}';
        } else {
            die(print_r(sqlsrv_errors(), true));
        }
    }
    
	// function postmethod($data) {
    //     include "index.php";
    
    //     // Escape input data to prevent SQL injection attacks
    //     $TSOID = sqlsrv_escape_string($conn, $data["TSOID"]);
    //     $InTime = sqlsrv_escape_string($conn, $data["InTime"]);
    //     $dealer = sqlsrv_escape_string($conn, $data["dealer"]);
    //     $Latitude = sqlsrv_escape_string($conn, $data["Latitude"]);
    //     $Longitude = sqlsrv_escape_string($conn, $data["Longitude"]);
    //     $location = sqlsrv_escape_string($conn, $data["location"]);
    //     $image = sqlsrv_escape_string($conn, $data["image"]);
        
    //             // Print the values of the parameters
    //     print_r($TSOID);
    //     print_r($InTime);
    //     print_r($dealer);
    //     print_r($Latitude);
    //     print_r($Longitude);
    //     print_r($location);
    //     print_r($image);

    //     // Build the SQL query with parameterized query to prevent SQL injection attacks
    //     $sql = "INSERT INTO TSOAttDealer (TSOID, xdate, InTime, dealer, Latitude, Longitude, location, ImagePath) 
    //             VALUES (?, GETDATE(), ?, ?, ?, ?, ?, ?)";
    
    //     // Prepare the query statement
    //     $stmt = sqlsrv_prepare($conn, $sql, array($TSOID, $InTime, $dealer, $Latitude, $Longitude, $location, $image));
    
    //     // Execute the query
    //     if (sqlsrv_execute($stmt)) {
    //         echo '{"result" : "Data Inserted"}';
    //     } else {
    //         // Handle errors
    //         die(print_r(sqlsrv_errors(), true));
    //     }
    
    //     // Clean up resources
    //     sqlsrv_free_stmt($stmt);
    // }
    




?> 
