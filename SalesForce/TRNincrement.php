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

	$sql = "UPDATE xtrn SET xnum += 1 where xtrn='SO--' and zid = '$zid'";
	   
	$stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
		//echo "False";
	}  else{
        
        http_response_code(200);
        sqlsrv_free_stmt( $stmt);
    }



    
 

}

?> 
