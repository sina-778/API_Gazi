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


function getmethod() {
    include "index.php";
    $zid = $_GET['zid'] ?? null;
    $tso = $_GET['tso'] ?? null;
    if (!$tso || !$zid) {
        http_response_code(400);
        die("Missing required parameter(s).");
    }

    
    $sql = "SELECT
    x1.zid, x1.xcode, x1.xlong
    FROM
    xcodes AS x1
    JOIN
    xcodes AS x2 ON x2.xtypeobj = x1.xlong
    WHERE
    x1.xtype = 'Product Nature'
    AND x1.zactive = 1
    AND x2.xtype = 'Territory'
    AND x2.zid = ?
    AND x2.xtso = ?";
   
    $params = array($zid,$tso);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if ($stmt === false) {
        http_response_code(500);
        die(print_r(sqlsrv_errors(), true));
    }
    if (!sqlsrv_execute($stmt)) {
        http_response_code(500);
        die(print_r(sqlsrv_errors(), true));
    }
    $rows = array();
    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      
        $rows[] = $r;
    }
   
	
    if (!$rows) {
        $default_values = array(
            "zid" => "",
            "xcode" => "",
            "xlong" => ""
        );
        echo json_encode(array($default_values));
    }  else {
		

    echo json_encode($rows);
    http_response_code(200);
    }
    sqlsrv_free_stmt($stmt);
}



?> 
