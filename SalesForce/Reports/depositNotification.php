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

	//this two line for getdata

	$zid = isset($_GET['zid']) ? $_GET['zid'] : die();
    $xcus = isset($_GET['xcus']) ? $_GET['xcus'] : die();


	$sql = "SELECT  top 10
	xdepositnum,
	ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
	ISNULL(xcus, '') AS xcus,
	ISNULL((SELECT xorg FROM cacus WHERE xcus = ardeposit.xcus AND zid = ardeposit.zid), '') AS cusname,
	ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = ardeposit.zid AND xnum = ardeposit.xstatus), '') AS xstatus,
	ISNULL(xbank, '') AS xbank,
	ISNULL((SELECT xname FROM cabank WHERE zid = ardeposit.zid AND xbank = ardeposit.xbank), '') AS bankName,
	ISNULL(xbranch, '') AS xbranch,
	ISNULL(xdepositref, '') AS xdepositref,
	ISNULL(xarnature, '') AS xarnature,
	ISNULL(xpaymenttype, '') AS xpaymenttype,
	ISNULL(xamount, 0) AS xamount
	FROM
	ardeposit
	WHERE
	xcus = '$xcus' 
	AND zid = $zid
	ORDER BY
	xdepositnum DESC";
	   
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
