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


	function getmethod() {
		include "index.php";
		//$zid = $_GET['zid'] ?? null;
		// $xstaff = $_GET['xstaff'] ?? null;
		// if (!$xstaff) {
		// 	http_response_code(400);
		// 	die("Missing required parameter(s).");
		// }
	
		
		$sql = "SELECT c.zid,
		c.xitem,
		ISNULL(c.xdesc, '') AS xdesc,
		ISNULL(c.xrate, 0) AS mainRate,
		CAST((ISNULL(p.xrate, 0) - (ISNULL(p.xrate, 0) * ISNULL(xdisc, 0) / 100)) AS decimal(10, 2)) AS xrate,
		CAST((ISNULL(p.xdealerp, 0) - (ISNULL(p.xdealerp, 0) * ISNULL(xdisc, 0) / 100)) AS decimal(10, 2)) AS xdealerp,
		CAST((ISNULL(p.xmrp, 0) - (ISNULL(p.xmrp, 0) * ISNULL(xdisc, 0) / 100)) AS decimal(10, 2)) AS xmrp,
		ISNULL(c.xcolor, '') AS xcolor,
		ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Color' AND xcode = c.xcolor AND zid = c.zid), '') AS color,
		ISNULL(c.xdisc, 0) AS xdisc,
		ISNULL(c.xcapacity, 0) AS xcapacity,
		ISNULL(c.xunit, 0) AS xunit,
		ISNULL(c.xunitsel, 0) AS xunitsel,
		ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Item Category' AND xcode = c.xcatitem AND zid = c.zid), '') AS xcatitem,
		ISNULL(c.xstype, 0) AS xstype,
		ISNULL((SELECT xlong FROM xcodes WHERE zactive = 1 AND xtype = 'Item Quality' AND xcode = c.xstype), '') AS stype,
		ISNULL(xpnature, '') AS xpnature,
		ISNULL(REPLACE(convert(varchar, c.xdateeff, 3),'0000-00-00',''),'-') AS xdateeff,
		ISNULL(REPLACE(convert(varchar, c.xdateexp, 3),'0000-00-00',''),'-') AS xdateexp
 		FROM caitem c
 		LEFT JOIN caitemprice p ON c.xitem = p.xitem AND c.zid = p.zid
 		WHERE c.xgitem = 1004
   		AND p.xrate > 0
   		AND p.xdealerp > 0
   		AND p.xmrp > 0";
	   
		
		$stmt = sqlsrv_prepare($conn, $sql);
	
		if ($stmt === false) {
			http_response_code(500);
			die(print_r(sqlsrv_errors(), true));
		}
		if (!sqlsrv_execute($stmt)) {
			http_response_code(500);
			die(print_r(sqlsrv_errors(), true));
		}
		$rows = array();
		while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		  
			$rows[] = $r;
		}
	   
		
		if (!$rows) {
			$default_values = array(
				"zid" => "",
				"xterritory" => "",
				"xtso" => "",
				"xzone" => "",
				"xsm" => "",
				"xzm" => "",
				"xdivision" => "",
				"xdm" => ""
			);
			echo json_encode(array($default_values));
		}  else {
			
	
		echo json_encode($rows);
		http_response_code(200);
		}
		sqlsrv_free_stmt($stmt);
	}
	


?> 
