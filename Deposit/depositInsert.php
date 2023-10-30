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
			echo '{"name" : "data not found"}';
		break;
	}


function postmethod($data){


	include "index.php";

	$zid = $data["zid"];
	$zauserid = $data["zauserid"];
	$xdepositnum = $data["xdepositnum"];
	$xcus = $data["xcus"];
	$xtso = $data["xtso"];
	$xdivision = $data["xdivision"];
	$xamount = $data["xamount"];
	$xbank = $data["xbank"];
	$xbranch = $data["xbranch"];
	$xnote = $data["xnote"];
	$xpreparer = $data["xpreparer"];
	$xdm = $data["xdm"];
	$xterritory = $data["xterritory"];
	$xzm = $data["xzm"];
	$xzone = $data["xzone"];
	$xarnature = $data["xarnature"];
	$xpaymenttype = $data["xpaymenttype"];
	$xcusbank = $data["xcusbank"];
	$xchequeno = $data["xchequeno"];
	$xref = $data["xref"];
	$xopincapply = $data["xopincapply"];
	$xdate = $data["xdate"];


	//$xdm, xdivision, xpnature, xzm, xzone

	$sql2 = "select isnull(xwh, '') xwh from xcodes where zid='$zid' and xcode='$xterritory' and xtype='Territory' and zactive='1'";
	$stmt2 = sqlsrv_query($conn, $sql2);

	if ($stmt2 === false) {
    die(print_r(sqlsrv_errors(), true));
	}
	$xwh = '';
	while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $xwh = $row['xwh'];
    echo "xwh: " . $xwh . "<br>";
	}

	sqlsrv_free_stmt($stmt2);

    // //pdmst.xstaff("xposition='"+#position+"'")
	// $sql3 = "select xstaff from pdmst where zid='$zid' and xposition='$xterritory' ";
	// $stmt3 = sqlsrv_query($conn, $sql3);

	// if ($stmt3 === false) {
    // die(print_r(sqlsrv_errors(), true));
	// }
	// $xstaff = '';

	// while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
    // $xstaff = $row['xstaff'];
    // echo "xstaff: " . $xstaff . "<br>";
	// }

	// sqlsrv_free_stmt($stmt3);


    
        
    $sql= "INSERT INTO ardeposit(ztime, zid, zauserid, xdepositnum, xdate, xcus, xtso, xdivision, xamount, xbank, 
    xbranch, xstatus, xstatusjv, xnote, xpreparer, xidsup, xdm, xterritory, xzm, xzone, xarnature, xpaymenttype,
    xcusbank, xchequeno, xwh, xdepositref, xref, xopincapply)
    VALUES(getdate(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 2, 1,  ?, ?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$params = array($zid, $zauserid, $xdepositnum, $xdate, $xcus, $xtso, $xdivision, $xamount, $xbank, $xbranch, $xnote,
		$xpreparer, $xdm, $xterritory, $xzm, $xzone, $xarnature, $xpaymenttype, $xcusbank, $xchequeno, $xwh, $xdepositnum, $xref, $xopincapply);


	$stmt = sqlsrv_prepare($conn, $sql, $params);

	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	}

	if (sqlsrv_execute($stmt)) {
		echo '{"result" : "Data Inserted"}';
	} else {
		die(print_r(sqlsrv_errors(), true));
	}


	$sql1 = "{CALL zabsp_Confirmed_Request(?, ?, ?, ?, ?, ?)}";
	$params1 = array($zid, $zauserid, $zauserid, $xwh, $xdepositnum, "Deposit Approval");

	$stmt1 = sqlsrv_query($conn, $sql1, $params1);


	//#spsql(zabsp_Confirmed_Request,#id,#user,#position,xfwh,xdepositnum,"Deposit Approval") 


	if ($stmt1 === false) {
		die(json_encode(array("message" => "Failed to call stored procedure")));
	} else{
        sqlsrv_free_stmt($stmt1);
	}

    $sql5 = "UPDATE xtrn SET xnum += 1 where xtrn='DP--' and zid = '$zid'";
	   
	$stmt5 = sqlsrv_query( $conn, $sql5 );

	if( $stmt === false) {
		http_response_code(404);
		die( print_r( sqlsrv_errors(), true) );
		//echo "False";
	}  else{
        
        http_response_code(200);
        sqlsrv_free_stmt( $stmt5);
    }

}




?> 
