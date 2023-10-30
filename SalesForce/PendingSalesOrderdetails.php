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


    include "index.php";

	
	$xtornum=$data["xtornum"];

	$sql = "select   (select xdesc from caitem where zid=opsodetail.zid and xitem=opsodetail.xitem)descr
    ,*  from opsodetail where xsonumber='$xtornum' ";

    
    $stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();


    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        
        $rows []  = $r;
    }

    echo json_encode($rows);

    sqlsrv_free_stmt($stmt);

}

?> 