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

	$sql = "select zid,xsp,xname,xterritory,xarea from cappo where xtype='TSO'";

	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();

	while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
      //echo $row['LastName'].", ".$row['FirstName']."<br />";
	  $rows[] = $r;
    //   echo json_encode($rows);
}

echo json_encode($rows);

sqlsrv_free_stmt( $stmt);

}


?>