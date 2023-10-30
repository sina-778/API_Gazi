<?php


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

	include "../db.php";
    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();
    $xposition = isset($_GET['xposition']) ? $_GET['xposition'] : die();
	

	$sql = "WITH CountsCTE AS (
		SELECT
			(SELECT count(xbomkey) FROM bmbomheader	WHERE LEFT(xbomkey, 4) = 'BM--'	AND xstatus NOT IN ('4', '', '6', '7') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)  AND zid = $zid)  as BOMCount,
			(SELECT count(xbatch) 	FROM moheader WHERE left(xbatch,4)='BAT-' AND xstatus not in ('4','','6','7')  AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as BatchCOunt,
			(SELECT count(xtornum)	FROM imtorheader	WHERE 	left(xtornum,4)='FINS'  and xstatustor not in ('4','','6','7')	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') AND zid = $zid) as InsCount
	)
	SELECT BOMCount, BatchCOunt, InsCount
	FROM CountsCTE;";
	   
	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
		//echo "False";
	}

	$rows = array();

	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      
	  $rows = $r;
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
