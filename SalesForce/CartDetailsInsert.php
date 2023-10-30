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
    $xrow=$data["xrow"];
	$xitem=$data["xitem"];
    $xqty=$data["xqty"];
    $subtotal=$data["subtotal"];
    $rate=$data["rate"];

	$sql= "INSERT into cartDetails(xrow, cid, xitem,xqty,subtotal,rate)
    VALUES ('$xrow','$cartid','$xitem','$xqty',$subtotal,$rate)";

	if (sqlsrv_query($conn, $sql)) {
		echo '{"result" : "Data Inserted"}';
		// code...
	} else{
        die( print_r( sqlsrv_errors(), true) );
		//echo '{"result" : "Data Not Inserted"}';
	}

}




?> 
