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




	// function postmethod($data) {
	// 	include "index.php";
	
	// 	$zid = $data["zid"];
	// 	$xsonum = $data["xsonum"];
	// 	$xtso = $data["xtso"];
	
	// 	// Get xzm from executiveview
	// 	$sql3 = "SELECT xzm FROM executiveview WHERE zid = ? AND xtso = ?";
	// 	$params3 = array($zid, $xtso);
	// 	$stmt3 = sqlsrv_query($conn, $sql3, $params3);
	
	// 	if ($stmt3 === false) {
	// 		die(print_r(sqlsrv_errors(), true));
	// 	}
	
	// 	$xzm = '';
	
	// 	while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
	// 		$xzm = $row['xzm'];
	// 		//echo "xzm: " . $xzm . "<br>";
	// 	}
	
	// 	sqlsrv_free_stmt($stmt3);
	
	// 	// Update opsoheader
	// 	$sql = "UPDATE opsoheader SET xdatecom = GETDATE(), xstatusso = 6, xidsup = ?, xstatus = 2 WHERE xsonumber = ?";
	// 	$params = array($xzm, $xsonum);
	// 	$stmt = sqlsrv_query($conn, $sql, $params);
	
	// 	if ($stmt === false) {
	// 		die(print_r(sqlsrv_errors(), true));
	// 	}
	

	// }
	
    function postmethod($data) {
        include "index.php";
    
        $zid = $data["zid"];
        $xsonum = $data["xsonum"];
        $xtso = $data["xtso"];
    
        // Get xzm from executiveview
        $sql3 = "SELECT xzm FROM executiveview WHERE zid = ? AND xtso = ?";
        $params3 = array($zid, $xtso);
        $stmt3 = sqlsrv_query($conn, $sql3, $params3);
    
        if ($stmt3 === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        $xzm = '';
    
        while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
            $xzm = $row['xzm'];
            echo "xzm: " . $xzm . "<br>";
        }
    
        sqlsrv_free_stmt($stmt3);
    
        // Update opsoheader
        $sql = "UPDATE opsoheader SET xdatecom = GETDATE(), xstatusso = 6, xidsup = ?, xstatus = 2 WHERE xsonumber = ?";
        $params = array($xzm, $xsonum);
        $stmt = sqlsrv_query($conn, $sql, $params);
    
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        http_response_code(200);
    }
    

// function postmethod($data){

//     include "index.php";

    
//     $zid=$data["zid"];
//     $xsonum = $data["xsonum"];
// 	$xtso = $data["xtso"];


// 	//pdmst.xstaff("xposition='"+#position+"'")
// 	$sql3 = "select xzm from executiveview where zid ='$zid' and xtso ='$xtso' ";
// 	$stmt3 = sqlsrv_query($conn, $sql3);

// 	if ($stmt3 === false) {
//     die(print_r(sqlsrv_errors(), true));
// 	}
// 	$xzm = '';

// 	while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
//     $xzm = $row['xzm'];
//     echo "xzm: " . $xzm . "<br>";
// 	}

// 	sqlsrv_free_stmt($stmt3);

//     $sql = "update opsoheader set xdatecom = GETDATE(), xstatusso= 6, xidsup = '$xzm', xstatus = 2 where xsonumber = '$xsonum'";

//     $stmt = sqlsrv_query( $conn, $sql );


// 	$sql1 = "SELECT 
//     ISNULL(xrow, '') AS xrow,
//     ISNULL(xitem, '') AS xitem,
//     ISNULL(xqtyreq, 0) AS xqtyreq,
//     ISNULL(xitem, '') AS itemName,
//     ISNULL(xdisc, 0.0) AS xdisc,
//     ISNULL(xdiscamt, 0.0) AS xdiscamt,
//     ISNULL(xdiscad, 0.0) AS xdiscad,
//     ISNULL(xdiscadamt, 0.0) AS xdiscadamt,
//     ISNULL(xrate, 0.0) AS xrate,
//     ISNULL(xlineamt, 0.0) AS xlineamt,
//     ISNULL(xpartno, '') AS xpartno
// 	FROM
//     opsodetail
// 	WHERE
//     zid = $zid
//     AND xsonumber = '$xsonum'";


  

//     $stmt = sqlsrv_query( $conn, $sql1 );


// 	if( $stmt === false) {
// 		die( print_r( sqlsrv_errors(), true) );
// 	}

// 	$rows = array();


//     while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

//         $rows [] = $r;

//     }

// 	if($rows == null){
// 		http_response_code(404);
//    	}else{
// 	   echo json_encode(($rows));
// 	   http_response_code(200);
// 	   sqlsrv_free_stmt( $stmt);
//    }

// }

?> 