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
function getmethod() {
    include "index.php";
    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();

    $sql = "SELECT RIGHT('000000' + CAST((xnum + 1) AS VARCHAR(7)), 6) AS DPnum FROM xtrn WHERE xtrn = 'DP--' AND zid = $zid";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        http_response_code(404);
        die(print_r(sqlsrv_errors(), true));
        //echo "False";
    }

    $rows = array();

    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows = $r;
    }

    if ($rows == null) {
        http_response_code(404);
    } else {
        // Convert the DPnum to a string before returning
        $rows['DPnum'] = strval($rows['DPnum']);
        echo json_encode(($rows));
        http_response_code(200);
        sqlsrv_free_stmt($stmt);
    }
}


?> 
