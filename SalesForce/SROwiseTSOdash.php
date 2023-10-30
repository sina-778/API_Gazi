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

    $zid = $_GET['zid'] ?? null;
    $tso = $_GET['tso'] ?? null;
    $month_per = $_GET['month_per'] ?? null;
    $xyear = $_GET['xyear'] ?? null;

    if (!$tso || !$month_per || !$xyear) {
        http_response_code(400);
        die("Missing required parameter(s).");
    }
 
    $sql = "select ISNULL(FLOOR(xrptcount), 0) mtarget, ISNULL(FLOOR(xrptcount/22), 0) dtarget,
            isnull(xprdcounter,0) mshopvisit, isnull(FLOOR(xqty),0) mqty,
            isnull((Select FLOOR(sum(d.xqtyord*(CASE when isnull(c.xpackqty,0)=0 then 1 else isnull(c.xpackqty,1) end))) from opdodetail d join opdoheader h on d.zid =h.zid and d.xdornum =h.xdornum join caitem c on d.zid =c.zid and d.xitem =c.xitem 
            where h.zid =$zid and h.xstatusord='Confirmed' and month(h.xdate)=$month_per and Year(h.xdate)=$xyear and (isnull(h.xsp,'')='$tso' OR isnull(xtso,'')='$tso')),0) as qtyAch

            from opsalestarget
	        where  xyear = ? and xper = ? and xstatus = 'Confirmed' and zid =? and
            xstaff = (select xstaff from cappo where xsp = ? and zid= ?)";
   
    $params = array( $xyear,$month_per, $zid,  $tso, $zid);
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
      
        $rows = $r;
    }
   
	
    if (!$rows) {
        $mqty = "";
        $mtarget = 0;
        $dtarget = 0;
        $mshopvisit = 0;
        echo json_encode(array(
            "mqty" => $mqty,
            "mtarget" => $mtarget,
            "dtarget" => $dtarget,
            "mshopvisit" => $mshopvisit,
            "qtyAch" => $qtyAch
        ));
    } else {
		

    echo json_encode($rows);
    http_response_code(200);
    }
    sqlsrv_free_stmt($stmt);
}



?> 
