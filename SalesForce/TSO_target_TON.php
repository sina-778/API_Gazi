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
  
// 	$xstaff = isset($_GET['xstaff']) ? $_GET['xstaff'] : die();
// 	$mothth_per = isset($_GET['mothth_per']) ? $_GET['mothth_per'] : die();
// 	$xyear = isset($_GET['xyear']) ? $_GET['xyear'] : die();
  
// 	$sql = "select FLOOR(xqty) xqty, xtargetamt from opsalestarget where xstaff = '$xstaff' and xper='$mothth_per' and xyear='$xyear' ";
	
// 	$stmt = sqlsrv_query( $conn, $sql );
  
// 	if( $stmt === false) {
// 	  http_response_code(404);
// 	  die( print_r( sqlsrv_errors(), true) );
// 	}
  
// 	$rows = array();
  
// 	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
// 	  $rows = $r;
// 	}
  
// 	if($rows == null){
// 	  $rows = array("xqty" => 0, "xtargetamt" => 0);
// 	  echo json_encode($rows);
// 	  http_response_code(200);
// 	}else{
// 	  echo json_encode($rows);
// 	  http_response_code(200);
// 	  sqlsrv_free_stmt( $stmt);
// 	}
// }
  

function getmethod() {
	global $conn;
	
	if (!isset($_GET['xstaff']) || !isset($_GET['mothth_per']) || !isset($_GET['xyear'])) {
	  http_response_code(400);
	  die(json_encode(array("error" => "Required parameters are missing.")));
	}
  
	$xstaff = $_GET['xstaff'];
	$mothth_per = $_GET['mothth_per'];
	$xyear = $_GET['xyear'];
  
	$sql = "SELECT FLOOR(xqty) xqty, xtargetamt
			FROM opsalestarget
			WHERE xstaff = ? AND xper = ? AND xyear = ?";
  
	$stmt = sqlsrv_prepare($conn, $sql, array($xstaff, $mothth_per, $xyear));
  
	if ($stmt === false) {
	  http_response_code(500);
	  die(print_r(sqlsrv_errors(), true));
	}
  
	$result = sqlsrv_execute($stmt);
  
	if ($result === false) {
	  http_response_code(500);
	  die(print_r(sqlsrv_errors(), true));
	}
  
	$rows = array();
  
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	  $rows = $row;
	}
  
	if ($rows == null) {
	  $rows = array("xqty" => 0, "xtargetamt" => 0);
	}
  
	echo json_encode($rows);
	http_response_code(200);
	sqlsrv_free_stmt($stmt);
}

?> 
