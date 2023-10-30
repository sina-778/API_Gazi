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
function getmethod(){

	include "index.php";

    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();
    //$xsonumber = isset($_GET['xsonumber']) ? $_GET['xsonumber'] : die();
    $xcus = isset($_GET['xcus']) ? $_GET['xcus'] : die();


	$sql = "SELECT TOP 10 ISNULL(xsonumber, '') AS xsonumber,
    ISNULL(REPLACE(convert(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate
    FROM (
    SELECT DISTINCT xsonumber, xdate
    FROM opsoqtyview
    WHERE zid = 400080
      AND xcus = '$xcus'
    ) AS subquery
    ORDER BY xdate DESC, xsonumber DESC;";
	   
	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
		//echo "False";
	}

	$rows = array();

	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      
	  $rows[] = $r;
    }

    if($rows == null){
         http_response_code(404);
    }else{
        echo json_encode(($rows));
        http_response_code(200);
        sqlsrv_free_stmt( $stmt);
    }
 

}

?> 
