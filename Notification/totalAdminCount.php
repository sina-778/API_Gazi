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


    include "db.php";

	$zid = $data["zid"];
	$xposition=$data["xposition"];


	$sql = "WITH CountsCTE AS (
		SELECT
			--Accounts Count
			(SELECT COUNT(xvoucher) FROM arhed WHERE left(xvoucher, 4) = 'ADAP' AND xstatusmr NOT IN ('4', '11', '18', '6', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS APCount,
			(SELECT COUNT(xvoucher) FROM arhed WHERE left(xvoucher, 4) = 'ADAR' AND xstatusmr NOT IN ('4', '11', '18', '6', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS ARCount,
			(SELECT COUNT(xbillno) FROM acbill WHERE left(xbillno, 4) = 'BL--' AND xstatus NOT IN ('4', '6', '') AND zid = $zid AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)) AS BILLCount,
			(SELECT COUNT(xgrninvno) FROM apsupinvm WHERE xstatus NOT IN ('1', '4', '7') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS DOCount,
			(SELECT COUNT(xbillno) FROM acbill WHERE left(xbillno, 4) = 'IOU-' AND xstatus NOT IN ('4', '6', '') AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)  AND zid = $zid) AS IOUCount,
			(SELECT COUNT(xadjnum) FROM acloanadj WHERE LEFT(xadjnum, 4) = 'LADJ' AND xstatus NOT IN ('1', '4') AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS Loan_AdjCount,
			(SELECT COUNT(xvoucher) FROM arhed WHERE xprime > 0 AND left(xvoucher, 2) = 'MR' AND xstatusmr IN ('2', '3') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS MRCount,
			(SELECT COUNT(xbillno) FROM acbill WHERE left(xbillno, 4) = 'PCR-' AND xstatus NOT IN ('4', '6', '') AND zid = $zid AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)) AS PettyCount,
			(SELECT COUNT(xvoucher) FROM arhed WHERE xprime > 0 AND left(xvoucher, 2) = 'PM' AND xstatusmr = '2' AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS PMCount,
			(SELECT COUNT(xvoucher) FROM acheader WHERE xstatusjv = '9' AND xstatus NOT IN ('4', '1', '7') AND (xsuperiorgl = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS VoucherCount,
			(SELECT count(xreqnum) 	FROM lcreqheader WHERE xstatus not in ('4','1')	AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)	AND zid = $zid ) as LCCount,
			--Inventory Count
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 4) IN ('DAM-') AND xstatustor NOT IN ('4', '11', '18', '6', '7', '1') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS DamageCount,
			(SELECT COUNT(xgrnnum) FROM pogrnheader WHERE (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid AND xstatusgrn = '1' AND LEFT(xgrnnum, 3) = 'GRN' AND xstatusdoc NOT IN ('1', '4', '7')) AS GRNCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE zid = $zid AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND LEFT(xtornum, 2) = 'RR' AND xstatustor NOT IN ('4','11','18','6','7','')) AS RRCount,
			(SELECT COUNT(xgrnnum) FROM pogrnheader WHERE zid = $zid AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND left(xgrnnum,3)='SQC' AND  xstatusgrn = '1' AND xstatusdoc not in ('1','4','7')) AS SQCCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 2) = 'SR' AND xstatustor NOT IN ('4', '11', '18', '6', '7', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS SRCount,
			(SELECT COUNT(xtornum) FROM imtorheader WHERE LEFT(xtornum, 4) IN ('TO--') AND xstatustor NOT IN ('4', '11', '19', '6', '7', '') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS TOCount,
			(SELECT count(xtornum) FROM imtorheader WHERE 	left(xtornum,4)='STO-'  and xstatustor not in ('4','11','18','6','7','')	AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as STOCount,
			(SELECT count(xdocnum)	FROM imdcheader WHERE 	left(xdocnum,4)='DDC-'  and xstatus not in ('4','','6','5','1')	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')  AND zid = $zid) as DepoDC_Count,

			--Production Count
			(SELECT count(xbomkey) FROM bmbomheader	WHERE LEFT(xbomkey, 4) = 'BM--'	AND xstatus NOT IN ('4', '', '6', '7') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)  AND zid = $zid)  as BOMCount,
			(SELECT count(xbatch)  FROM moheader WHERE left(xbatch,4)='BAT-' AND xstatus not in ('4','','6','7')  AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as BatchCOunt,
			(SELECT count(xtornum)	FROM imtorheader	WHERE 	left(xtornum,4)='FINS'  and xstatustor not in ('4','','6','7')	AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') AND zid = $zid) as InsCount,
			--Sales Count
			(SELECT count(xdornum) as total	FROM opdoheader	WHERE LEFT(xdornum, 3) = 'DC-' AND xstatus NOT IN ('4', '1', '', '5') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition)	AND zid = $zid)  as DC_Count,
			(SELECT count(xdepositnum) 	FROM ardeposit	WHERE LEFT(xdepositnum, 4) = 'DP--' AND xstatus NOT IN ('4', '') 	AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as DepositCount,
			(SELECT count(xsonumber) FROM opsoheader WHERE xstatus IN ('2', '3') AND LEFT(xsonumber, 2) = 'SO' AND xidsup =$xposition	AND zid = $zid) as SOCount,
			(SELECT count(xcrnnum) as total	FROM opcrnheader WHERE LEFT(xcrnnum, 4) = 'SLR-' AND xstatus NOT IN ('4', '', '6', '5', '3') AND (xidsup = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) as SR_Count,
			(SELECT count(a.xdocnum) FROM imdcheader a join imdcshortview b on a.zid = b.zid and a.xdocnum = b.xdocnum 	WHERE a.zid = $zid AND (a.xidsup = '$xposition' OR a.xsuperior2 = '$xposition' OR a.xsuperior3 = '$xposition') AND left(a.xdocnum,4)='DDC-' AND  xstatusdoc='19') as DDC_Count,
			(SELECT count(xcus) from cacus where xstatus not in ('5','4','1') AND xtype='Customer'	AND zid = $zid AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')) as CUS_Count,
			(SELECT count(xrow) from caexecutivechange where xstatus in ('2','3') AND zid = $zid  AND (xidsup = '$xposition') ) as EXC_Count,
			--SCM_Count
			(SELECT count(xporeqnum) FROM poreqheader WHERE xtype='Cash' and left(xporeqnum,4) in ('PADJ') and xstatusreq  not in ('4','7','','0') AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid) AS PADJ_Count,
			(SELECT count(xporeqnum) FROM poreqheader WHERE LEFT(xporeqnum, 4) IN ('PR--', 'JN--', 'DOC-') AND xtype = 'Cash' AND xstatusreq NOT IN ('4', '7') AND (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND zid = $zid)  as Cash_Adv_Count,
			--(SELECT count(xporeqnum)  FROM poreqheader 	WHERE (xsuperiorsp = $xposition OR xsuperior2 = $xposition OR xsuperior3 = $xposition) AND LEFT(xporeqnum, 4) IN 	('PR--','JN--') AND xtype='CS' AND xstatusreq NOT IN ('4','7') AND zid=$zid ) as CS_Count,
			(SELECT count(xpornum) 	FROM poordheader WHERE LEFT(xpornum, 4) IN ('PO--', 'WO--') AND xstatus NOT IN ('4', '7') and (xidsup =$xposition or xsuperior2= $xposition or xsuperior3= $xposition ) and zid=$zid) AS POCount,
			(SELECT count(xtornum) 	FROM imtorheader WHERE left(xtornum,3)='SPR'  and xstatustor not in ('4','11','18','6','7','') and (xidsup='$xposition' or xsuperior2='$xposition' or xsuperior3='$xposition') and zid=$zid) AS SPRCount
	)
	SELECT
		APCount + ARCount + BILLCount + DOCount + IOUCount + Loan_AdjCount + MRCount + PettyCount + PMCount + VoucherCount + LCCount AS Accounts_Count,
		DamageCount + GRNCount + RRCount + STOCount + SQCCount + SRCount + TOCount + DepoDC_Count AS Inventory_Count,
		BOMCount+BatchCOunt+InsCount AS Production_Count,
		DC_Count+DepositCount+SOCount+SR_Count+DDC_Count+CUS_Count+EXC_Count AS Sales_Count,
		PADJ_Count+Cash_Adv_Count+POCount+SPRCount AS SCM_Count
	
	FROM CountsCTE;";
    
    $stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();


    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $rows = $r;
    }

    echo json_encode($rows);

    sqlsrv_free_stmt($stmt);

}

?> 