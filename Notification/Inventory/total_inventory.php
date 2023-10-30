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
	

	$sql = "
	WITH CountsCTE AS (
			SELECT
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 4) IN ('DAM-') AND xstatustor NOT IN ('4', '11', '18', '6', '7', '1') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS DamageCount,
			(SELECT COUNT(xgrnnum) FROM pogrnheader WHERE (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid AND xstatusgrn = '1' AND LEFT(xgrnnum, 3) = 'GRN' AND xstatusdoc NOT IN ('1', '4', '7')) AS GRNCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE zid = $zid AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND LEFT(xtornum, 2) = 'RR' AND xstatustor NOT IN ('4','11','18','6','7','')) AS RRCount,
			(SELECT COUNT(xgrnnum) FROM pogrnheader WHERE zid = $zid AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND left(xgrnnum,3)='SQC' AND  xstatusgrn = '1' AND xstatusdoc not in ('1','4','7')) AS SQCCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 2) = 'SR' AND xstatustor NOT IN ('4', '11', '18', '6', '7', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS SRCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 4) IN ('TO--') AND xstatustor NOT IN ('4', '11', '19', '6', '7', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS TOCount,
			(SELECT count(xtornum)	FROM imtorheader WHERE 	left(xtornum,4)='STO-'  and xstatustor not in ('4','11','18','6','7','')	AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as STOCount,
			(SELECT count(xdocnum)	FROM imdcheader WHERE 	left(xdocnum,4)='DDC-'  and xstatus not in ('4','','6','5','1')	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')  AND zid = $zid) as DepoDC_Count
		)
		SELECT DamageCount, GRNCount, RRCount, RRCount, SQCCount, SRCount, TOCount, STOCount, DepoDC_Count
		FROM CountsCTE";
	   
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
