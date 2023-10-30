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


	$cartid=$data["cartid"];
    $xso=$data["xso"];
	$xcus=$data["xcus"];
    $xtime=$data["xtime"];
    $total=$data["total"];
    $xstatus=$data["xstatus"];

	$sql= "INSERT into cartTable(ztime, cartid, xso,xcus,xtime,total,xstatus)
    VALUES (getdate(),'$cartid','$xso','$xcus', '$xtime','$total','$xstatus')";

	if (sqlsrv_query($conn, $sql)) {
		echo '{"result" : "Data Inserted"}';
		// code...
	} else{
        die( print_r( sqlsrv_errors(), true) );
		//echo '{"result" : "Data Not Inserted"}';
	}

}




?> 
