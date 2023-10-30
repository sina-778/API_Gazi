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
    //$zid = $_GET['zid'] ?? null;
    $xstaff = $_GET['xstaff'] ?? null;
    if (!$xstaff) {
        http_response_code(400);
        die("Missing required parameter(s).");
    }

    
    $sql = "select distinct zid,isnull(xterritory,'') xterritory, isnull(xtso,'') xtso,xzone,xzm,xdivision,xdm from cacus 
	where (xtso= ? or xdm= ? or xzm= ?) and xzm <> '' and xdm <> '' and xtso <> ''";
   
    $params = array($xstaff,$xstaff,$xstaff);
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
            "xterritory" => "",
            "xtso" => "",
            "xzone" => "",
            "xsm" => "",
            "xzm" => "",
            "xdivision" => "",
            "xdm" => ""
        );
        echo json_encode(array($default_values));
    }  else {
		

    echo json_encode($rows);
    http_response_code(200);
    }
    sqlsrv_free_stmt($stmt);
}



?> 
