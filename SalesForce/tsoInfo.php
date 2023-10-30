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
    $xstaff90 = $_GET['xstaff90'] ?? null;
    $xstaff210 = $_GET['xstaff210'] ?? null;
    $month_per = $_GET['month_per'] ?? null;
    $xyear = $_GET['xyear'] ?? null;
    if (!$xstaff90 || !$xstaff210 || !$month_per || !$xyear) {
        http_response_code(400);
        die("Missing required parameter(s).");
    }

    // As per Tuhin vai
    // $sql = "SELECT zid,  isnull(xwh,'') xwh ,xsp, ISNULL(xstaff, '') AS xstaff, ISNULL(xsm, '') AS xsm, ISNULL(xrsm, '') AS xrsm, ISNULL(xname, '') AS xname, ISNULL(xphone, '') AS xphone,
    // ISNULL(xterritory, '') AS xterritory, isnull((SELECT ISNULL(FLOOR(xrptcount), 0) FROM opsalestarget WHERE xstaff = ? AND xper = ? AND xyear = ? AND xstatus = 'Confirmed'),0) AS mtarget,
    // isnull((SELECT ISNULL(FLOOR(xrptcount/22), 0) FROM opsalestarget WHERE xstaff = ? AND xper = ? AND xyear = ? AND xstatus = 'Confirmed'),0) AS dtarget,
    // isnull((SELECT isnull(xprdcounter,0) FROM opsalestarget WHERE xstaff = '$xstaff' AND xper = $month_per AND xyear = $xyear AND xstatus = 'Confirmed'),0) AS mshopvisit
    // FROM cappo WHERE zactive = 1 AND xsp = (SELECT DISTINCT top 1 xsp FROM cacus WHERE zid in( 300210, 100090) AND xsp = (SELECT TOP 1 xsp FROM cappo WHERE zid in (300210 ,100090) AND zactive = 1 AND xstaff = ?))";
   
    $sql = "SELECT zid,  isnull(xwh,'') xwh ,xsp, ISNULL(xstaff, '') AS xstaff, ISNULL(xsm, '') AS xsm, ISNULL(xrsm, '') AS xrsm, ISNULL(xname, '') AS xname, ISNULL(xphone, '') AS xphone,
    ISNULL(xterritory, '') AS xterritory, isnull((SELECT ISNULL(FLOOR(xrptcount), 0) FROM opsalestarget WHERE xstaff in( ?, ?) AND xper = ? AND xyear = ? AND xstatus = 'Confirmed'),0) AS mtarget,
    isnull((SELECT ISNULL(FLOOR(xrptcount/22), 0) FROM opsalestarget WHERE xstaff in( ?, ?) AND xper = ? AND xyear = ? AND xstatus = 'Confirmed'),0) AS dtarget,
    isnull((SELECT isnull(xprdcounter,0) FROM opsalestarget WHERE xstaff in ('$xstaff90','$xstaff210') AND xper = $month_per AND xyear = $xyear AND xstatus = 'Confirmed'),0) AS mshopvisit,
    isnull((SELECT ISNULL(FLOOR(xqty), 0) FROM opsalestarget WHERE xstaff in( ?, ?) AND xper = ? AND xyear = ? AND xstatus = 'Confirmed'),0) AS qtyTarget,
    isnull((Select FLOOR(sum(d.xqtyord*(CASE when isnull(c.xpackqty,0)=0 then 1 else isnull(c.xpackqty,1) end))) from opdodetail d join opdoheader h on d.zid =h.zid and d.xdornum =h.xdornum join caitem c on d.zid =c.zid and d.xitem =c.xitem 
    where h.zid =cappo.zid and h.xstatusord='Confirmed' and month(h.xdate)=? and Year(h.xdate)=? and (isnull(h.xsp,'')=cappo.xsp OR isnull(xtso,'')=cappo.xsp)),0) AS qtyAch
    FROM cappo WHERE zactive = 1 AND xstaff in( ?, ?)";
   
    $params = array($xstaff90, $xstaff210, $month_per, $xyear, $xstaff90, $xstaff210, $month_per, $xyear, $xstaff90, $xstaff210, $month_per, $xyear, $month_per, $xyear,   $xstaff90, $xstaff210);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

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
            "zid" => 100090,
            "xwh" => "",
            "xsp" => "",
            "xstaff" => "",
            "xsm" => "",
            "xrsm" => "",
            "xname" => "",
            "xphone" => "",
            "xterritory" => "",
            "mtarget" => 0,
            "dtarget" => 0,
            "mshopvisit" => 0,
            "qtyTarget" => 0,
            "qtyAch" => 0
        );
        echo json_encode(array($default_values));
    }  else {
		

    echo json_encode($rows);
    http_response_code(200);
    }
    sqlsrv_free_stmt($stmt);
}



?> 
