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
	$xcus = isset($_GET['xcus']) ? $_GET['xcus'] : die();

	$sql = "SELECT 
    ISNULL(zid, '') AS zid,
    ISNULL(xcus, '') AS xcus,
    ISNULL((SELECT xorg FROM cacus WHERE zid = oparhedview.zid AND xcus = oparhedview.xcus), '') AS dealerName,
    ISNULL(xpnature, '') AS xpnature,
    CASE
        WHEN xbalance >= 0 THEN CONCAT(FORMAT(xbalance, 'N2'), '   (Dr)')
        ELSE CONCAT('-', FORMAT(ABS(xbalance), 'N2'), '   (Cr)')
    END AS xbalance
	FROM
    oparhedview
	WHERE
    zid = $zid 
	and xcus = '$xcus'";
	   
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
