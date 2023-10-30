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
	

	$sql = "SELECT count(xdornum) as total,
    (SELECT count(xdornum) as total
	FROM 
	opdoheader
	WHERE 
	LEFT(xdornum, 3) = 'DC-' AND xstatus NOT IN ('4', '1', '', '5')
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	AND zid = '$zid'
	)  as DC_Count,

	(SELECT count(xdepositnum) 
	FROM ardeposit
	WHERE LEFT(xdepositnum, 4) = 'DP--' AND xstatus NOT IN ('4', '')
	AND zid = $zid
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	) as DepositCount,

	(SELECT count(xsonumber) 
	FROM opsoheader
	WHERE xstatus IN ('2', '3')
	AND LEFT(xsonumber, 2) = 'SO'
	AND xidsup ='$xposition'
	AND zid = $zid
	) as SOCount,

	(SELECT count(xcrnnum) as total
	FROM
	opcrnheader
	WHERE
	LEFT(xcrnnum, 4) = 'SLR-'
	AND xstatus NOT IN ('4', '', '6', '5', '3')
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	AND zid = '$zid'
	) as SR_Count,

	(SELECT count(a.xdocnum) FROM imdcheader a
	join imdcshortview b 
	on a.zid = b.zid and a.xdocnum = b.xdocnum 
	WHERE 
	a.zid = $zid  
	AND (a.xidsup = '$xposition' OR a.xsuperior2 = '$xposition' OR a.xsuperior3 = '$xposition') 
	AND left(a.xdocnum,4)='DDC-'
	AND  xstatusdoc='19') as DDC_Count, 

	(SELECT count(xcus) 
	from cacus
	where xstatus not in ('5','4','1')
	AND xtype='Customer'
	AND zid = $zid  
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	) as CUS_Count,

	(SELECT count(xrow) 
	from caexecutivechange
	where xstatus in ('2','3')
	AND zid = $zid  
	AND (xidsup = '$xposition') 
	) as EXC_Count

	FROM
	opdoheader
	WHERE 
	LEFT(xdornum, 3) = 'DC-' AND xstatus NOT IN ('4', '1', '', '5')
	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
	AND zid = '$zid'";
	   
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
