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

    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();
	$staff = isset($_GET['staff']) ? $_GET['staff'] : die();

	$sql = "select distinct zid,isnull(xterritory,'') xterritory, isnull(xsp,'') xsp, isnull(xso,'') xso,
    isnull((select isnull(xname,'') from pdmst where zid= $zid and xstaff = (select  top 1 xstaff from cappo where xsp = cacus.xso and zid= $zid)),'') name,
	(select  top 1 xtype from cappo where zid = $zid  and zactive = 1 and xstaff =  '$staff' ) xrole
    from cacus 
	where zid = $zid and xso <>'' and (xsp= (select top 1 xsp  from cappo where zid = $zid and zactive = 1 and xstaff = '$staff')) ";

	
	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
	}



	$rows = array();

	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {      
	    $rows[] = $r;
	}



	if($rows == null){
	    $rows["xterritory"] = $rows["xterritory"] ?? '';
	    $rows["xsp"] = $rows["xsp"] ?? '';
		echo json_encode(($rows));
	} else {
	    echo json_encode(($rows));
	    http_response_code(200);
	    sqlsrv_free_stmt( $stmt );
	    
	}
}


?> 
