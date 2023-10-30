<?php

include "index.php";

header('content-type: application/json');

	$request = $_SERVER['REQUEST_METHOD'];


	switch ( $request) {


		case 'GET':
			// code...

			getmethod();
		break;
		

		default:
			// code...

			echo '{"name" : "data not found"}';
		break;
	}


// data get part
// function getmethod(){

// 	include "index.php";
// 	$zid = isset($_GET['zid']) ? $_GET['zid'] : die();
// 	$xcus = isset($_GET['xcus']) ? $_GET['xcus'] : die();


// 	$sql = "SELECT 
//     ISNULL(xdepositnum, '') AS xdepositnum,
//     ISNULL(xcus, '') AS xcus,
//     ISNULL(xorg, '') AS xorg,
//     ISNULL(xamount, 0) AS xamount,
//     ISNULL(xlineamt, 0) AS xlineamt,
//     ISNULL(xbalance, 0) AS xbalance
// 	FROM 
//     ardepositsoview
// 	WHERE  zid = $zid
// 	AND xcus = '$xcus'
//     AND xbalance > 0 
//     AND xstatus <> '5'";
	   
// 	$stmt = sqlsrv_query( $conn, $sql );

// 	if( $stmt === false) {
// 		http_response_code(404);
// 		die( print_r( sqlsrv_errors(), true) );
// 		//echo "False";
// 	}

// 	$rows = array();

// 	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      
// 	  $rows[] = $r;
// 	}

//     if($rows == null){
//          http_response_code(404);
//     }else{
//         echo json_encode(($rows));
//         http_response_code(200);
//         sqlsrv_free_stmt( $stmt);
//     }
 

// }

function getmethod() {
	include "index.php";
	$zid = isset($_GET['zid']) ? $_GET['zid'] : die();
	$xcus = isset($_GET['xcus']) ? $_GET['xcus'] : die();

	$sql = "SELECT 
		ISNULL(xdepositnum, '') AS xdepositnum,
		ISNULL(xcus, '') AS xcus,
		ISNULL(xorg, '') AS xorg,
		ISNULL(xamount, 0) AS xamount,
		ISNULL(xlineamt, 0) AS xlineamt,
		ISNULL(xbalance, 0) AS xbalance
	FROM 
		ardepositsoview
	WHERE  
		zid = $zid
		AND xcus = '$xcus'
		AND xbalance > 0 
		AND xstatus <> '5'";

	$stmt = sqlsrv_query($conn, $sql);

	if ($stmt === false) {
		http_response_code(404);
		die(print_r(sqlsrv_errors(), true));
	}

	$rows = array();
	$emptyRow = array(
		"xdepositnum" => "",
		"xcus" => "",
		"xorg" => "",
		"xamount" => "0",
		"xlineamt" => "0",
		"xbalance" => "0"
	);
	$rows[] = $emptyRow;

	while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$rows[] = $r;
	}

	if ($rows == null) {
		http_response_code(404);
	} else {
		echo json_encode($rows);
		http_response_code(200);
		sqlsrv_free_stmt($stmt);
	}
}


?> 
