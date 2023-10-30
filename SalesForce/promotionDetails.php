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



   
	$sql = "SELECT ISNULL(zid, '') AS zid,
    ISNULL(xrow, '') AS xrow,
    ISNULL(xtrnnum, '') AS xtrnnum,
    ISNULL(xstype, '') AS xstype,
    ISNULL(xcolor, '') AS xcolor,
    ISNULL(xtype, '') AS xtype,
    ISNULL(xfslab, 0) AS xfslab,
    ISNULL(xtslab, 0) AS xtslab,
    ISNULL(xamount, 0) AS xamount
    FROM oppromodetail
    WHERE zactive = 1";
	   
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
