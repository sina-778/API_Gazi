<?php

header('content-type: application/json');

	$request = $_SERVER['REQUEST_METHOD'];


	switch ( $request) {


	

		case 'POST':
			// code...
			$data=json_decode(file_get_contents('php://input'),true);
			postmethod($data);
		break;


		default:
			// code...

			echo '{"name" : "data not found"}';
		break;
	}




function postmethod($data){


    include "../index.php";

	$zid = $data["zid"];
	$xposition=$data["xposition"];

  
    //$xtypeleave=$data["xtypeleave"];

	$sql = "
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

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
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

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
	) as PettyCount,

	(SELECT count(xvoucher) as total
		FROM arhed
		WHERE xprime > 0 AND left(xvoucher,2)='PM' and xstatusmr='2'
		AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) 
		AND zid = $zid
	) as PMCount

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
	) as VoucherCount

	(SELECT count(xvoucher) as total
		FROM acheader
		WHERE xstatusjv = '9'
		AND xstatus NOT IN ('4', '1', '7')
		AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)
		AND zid = $zid 
	) as VoucherCount

	
	";




    
    $stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();


    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        //echo $row['LastName'].", ".$row['FirstName']."<br />";
        $rows = $r;
      //   echo json_encode($rows);
    }

    echo json_encode($rows);

    sqlsrv_free_stmt($stmt);

}

?> 