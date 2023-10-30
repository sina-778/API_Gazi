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
		$Latitude = $data["Latitude"];
		$Longitude = $data["Longitude"];
		$location = $data["location"];
		$image = $data["image"];


		$sql= "INSERT INTO TSOAttendance (TSOID, xdate, InTime, OutTime, Latitude, Longitude, location,ImagePath) 
		VALUES ('$TSOID', getdate(), '$InTime', '$OutTime', '$Latitude', '$Longitude', '$location', '$image')";

        // $sql = "
        // IF EXISTS (SELECT * FROM TSOAttendance WHERE TSOID='$TSOID' and xdate = CONVERT(DATE, GETDATE()))

        // BEGIN
        // UPDATE TSOAttendance 
        //     SET OutTime = '$OutTime', Latitude = '$Latitude', Longitude = '$Longitude', location = '$location'
        //     WHERE  TSOID='$TSOID' and  xdate  = CONVERT(DATE, GETDATE())
        // END
        // ELSE
        // BEGIN
        //     INSERT INTO TSOAttendance (xdate, TSOID, InTime, OutTime, Latitude, Longitude, location)
        //     VALUES ( CONVERT(DATE, GETDATE()),'$TSOID', '$InTime', '$OutTime', '$Latitude', '$Longitude', '$location')
        // END";
	
		if (sqlsrv_query($conn, $sql)) {
			echo '{"result" : "Data Inserted"}';
		} else {
			die(print_r(sqlsrv_errors(), true));
		}
	}
	




?> 
