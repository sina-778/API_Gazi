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
	

	$sql = " select  count(xvoucher) as test,
    (
		SELECT count(xvoucher) as total
		FROM arhed
		WHERE   left(xvoucher,4)='ADAP'  and xstatusmr not in ('4','11','18','6','')
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid
	)  as APCount,

	(
		SELECT count(xvoucher) as total
		FROM arhed
		WHERE  left(xvoucher,4)='ADAR'  and xstatusmr not in ('4','11','18','6','')
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid
	) as ARCount,

	(SELECT count(xbillno) as total
        FROM acbill
        WHERE 
        left(xbillno, 4) = 'BL--'  
        AND xstatus NOT IN ('4', '6', '') 
        AND zid = $zid and (xsuperiorgl= $xposition or xsuperior2= $xposition or xsuperior3=$xposition)  
	) as BILLCount,

	(SELECT count(xgrninvno) as total
	FROM apsupinvm
	WHERE 
	xstatus not in ('1','4','7')
	AND zid = '$zid'
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	) as DOCount,

	(SELECT count(xbillno) as total
		FROM acbill
		WHERE left(xbillno, 4) = 'IOU-' AND xstatus NOT IN ('4', '6', '')
		AND (xsuperiorgl=$xposition or xsuperior2=$xposition or xsuperior3=$xposition)
		AND zid = $zid
	) as IOUCount,

	(SELECT count(xvoucher) as total
		FROM acloanadj
		WHERE LEFT(xadjnum, 4) = 'LADJ' AND xstatus NOT IN ('1', '4')
		AND (xsuperiorgl=$xposition or xsuperior2=$xposition or xsuperior3=$xposition) 
		AND zid = $zid
	) as Loan_AdjCount,

	(SELECT count(xvoucher) as total
		FROM arhed
		WHERE xprime > 0 AND left(xvoucher, 2) = 'MR' AND xstatusmr IN ('2', '3')
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid
	) as MRCount,

	(SELECT count(xbillno) as total
	FROM acbill
	WHERE  left(xbillno,4)='PCR-'  and xstatus not in ('4','6','')
	AND zid = '$zid'
	AND (xsuperiorgl = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	) as PettyCount,

	(SELECT count(xvoucher) as total
		FROM arhed
		WHERE xprime > 0 AND left(xvoucher,2)='PM' and xstatusmr='2'
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid
	) as PMCount,

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
	) as VoucherCount,

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
	) as VoucherCount,
	(SELECT count(xreqnum) as total
	FROM lcreqheader
	WHERE xstatus not in ('4','1')
	AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
	AND zid = $zid 
	) as LCCount

	FROM arhed
		WHERE   left(xvoucher,4)='ADAP'  and xstatusmr not in ('4','11','18','6','')
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid";
	   
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
