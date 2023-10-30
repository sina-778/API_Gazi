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

    //$type = isset($_GET['type']) ? $_GET['type'] : die();
    

    //xmadd,xphone,xgcus,xstatuscus,xwh,xdistrict,xthana,xpaymentterm,xcontact,xcontactphn,xtype,xsubcat,xsubcat 
	//$sql = "select xitem,xdesc,xrate,xvatrate,xvatamt,CAST((xrate+xvatamt) AS DECIMAL(18,2)) as totrate,xpackqty,xunitsel,CAST(xdisc AS DECIMAL(18,2)) as xdisc,xdiscstatus,isnull(xnote1, '') as note from caitem where xtype='Salable' and zactive=1 and xsubcat= '$type'";
	 
	$sql = "select xrow,xcus,xitem,isnull(CAST(xrate + ( select top 1 xvatrate from caitem where xitem =cacusprice.xitem and zid =cacusprice.zid  )as int),0) as xrate,
	isnull(xcost,0) xcost,  ISNULL(REPLACE(convert(varchar, xdateeff, 3),'0000-00-00',''),'-') AS xdateeff,
	ISNULL(REPLACE(convert(varchar, xdateexp, 3),'0000-00-00',''),'-') AS xdateexp from cacusprice
	where  zactive = 1 and  xdateeff <= GETDATE() and xdateexp >= GETDATE()";

	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
		//echo "False";
	}

	$rows = array();

	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      
	  $rows[] = $r;
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
