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

    $staff = isset($_GET['staff']) ? $_GET['staff'] : die();


	$sql = "SELECT xsonumber,ISNULL(REPLACE(convert(varchar, xdate, 3),'0000-00-00',''),'-') AS xdate,xcus,(select xorg from cacus where xcus = opsoheader.xcus and zid = opsoheader.zid) as cusname,
	(select xlong from zstatus where zactive = 1 and  zid= opsoheader.zid and xnum =opsoheader.xstatus ) xstatusso,xterritory FROM opsoheader WHERE  xdate BETWEEN DATEADD(month, -1, GETDATE()) AND GETDATE() and xpreparer= '$staff' order by xsonumber desc";
	   
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
