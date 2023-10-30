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


    $xposition=$data["xposition"];
	$xlocation=$data["xlocation"];
    

	$sql= "INSERT into pdatapp(xposition,xtimein,xmodel,xlocation)
    VALUES ($xposition,GETDATE(),'APP','$xlocation')";



	if (sqlsrv_query($conn, $sql)) {
		echo '{"result" : "Data Inserted"}';
		// code...
	} else{
        die( print_r( sqlsrv_errors(), true) );
		echo '{"result" : "Data Not Inserted"}';
	}

}

?> 
