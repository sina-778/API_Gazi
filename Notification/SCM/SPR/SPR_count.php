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

	$zid = $data["zid"];
	$xposition=$data["xposition"];

  
    //$xtypeleave=$data["xtypeleave"];

	$sql = "select  count(xtornum) as total
    from imtorheader where zid= $zid and (xidsup='$xposition' or xsuperior2='$xposition' or xsuperior3='$xposition') and  left(xtornum,3)='SPR' and xstatustor not in ('Approved','Checked','Partial Issue','Confirmed','Dismissed','')	";



    
    $stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();


    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        //echo $row['LastName'].", ".$row['FirstName']."<br />";
        $rows = $r;
      //   echo json_encode($rows);
    }

    echo json_encode($rows);

    sqlsrv_free_stmt($stmt);

}

?> 