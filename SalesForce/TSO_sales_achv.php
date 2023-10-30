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


// data get part
function getmethod(){

	include "index.php";

    $xtso = isset($_GET['xtso']) ? $_GET['xtso'] : die();
    $mothth_per = isset($_GET['mothth_per']) ? $_GET['mothth_per'] : die();
	$xyear = isset($_GET['xyear']) ? $_GET['xyear'] : die();

    //xmadd,xphone,xgcus,xstatuscus,xwh,xdistrict,xthana,xpaymentterm,xcontact,xcontactphn,xtype,xsubcat,xsubcat 
	$sql = "select isnull(sum((isnull(d.xqtydoc*isnull(a.xpackqty,0),0)/1000)),0) as tso_sales
	from opdcheader h join opdcdetail d on d.zid=h.zid and d.xdocnum=h.xdocnum 
	join cappo c on c.zid=h.zid and c.xsp=h.xtso 
	join caitem a on a.zid=d.zid and a.xitem=d.xitem --h.xcus='CUSC000001'
	where h.xtso='$xtso'  and h.xstatusdoc='Confirmed' and  month(h.xdate)='$mothth_per' and year(h.xdate)='$xyear' ";
	   
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
