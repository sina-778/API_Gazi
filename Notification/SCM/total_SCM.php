<?php


header('content-type: application/json');

	$request = $_SERVER['REQUEST_METHOD'];


	switch ( $request) {


		case 'GET':
			getmethod();
		break;
		default:
			echo '{"name" : "data not found"}';
		break;
	}


function getmethod(){

	include "../db.php";
    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();
    $xposition = isset($_GET['xposition']) ? $_GET['xposition'] : die();
	

	$sql = "WITH CountsCTE AS (
		SELECT
		(SELECT count(xporeqnum) FROM poreqheader WHERE xtype='Cash' and left(xporeqnum,4) in ('PADJ') and xstatusreq  not in ('4','7','','0') AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS PADJ_Count,
        (SELECT count(xporeqnum) FROM poreqheader WHERE LEFT(xporeqnum, 4) IN ('PR--', 'JN--', 'DOC-') AND xtype = 'Cash' AND xstatusreq NOT IN ('4', '7') AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid)  as Cash_Adv_Count,
		(SELECT count(xporeqnum)  FROM poreqheader 	WHERE (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND LEFT(xporeqnum, 4) IN 	('PR--','JN--') AND xtype='CS' AND xstatusreq NOT IN ('4','7') AND zid=$zid ) as CS_Count,
		(SELECT count(xpornum) 	FROM poordheader WHERE LEFT(xpornum, 4) IN ('PO--', 'WO--') AND xstatus NOT IN ('4', '7') and (xidsup =$xposition or xsuperior2= $xposition or xsuperior3= $xposition ) and zid=$zid) AS POCount,
		(SELECT count(xtornum) 	FROM imtorheader WHERE left(xtornum,3)='SPR'  and xstatustor not in ('4','11','18','6','7','') and (xidsup='$xposition' or xsuperior2='$xposition' or xsuperior3='$xposition') and zid=$zid) AS SPRCount
	)
	SELECT PADJ_Count, Cash_Adv_Count, 0 As CS_Count, POCount, SPRCount
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
