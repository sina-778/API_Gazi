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
	$xtornum = $data["xtornum"];
	$xdate = $data["xdate"];
	$xcus = $data["xcus"];
	$xpreparer = $data["xpreparer"];
	$xterritory = $data["xterritory"];
	$xtso = $data["xtso"];
	$xdm = $data["xdm"];
	$xtotamt = $data["xtotamt"];
	$xfwh = $data["xfwh"];
	$xdivision = $data["xdivision"];
	$xpnature = $data["xpnature"];
	$xzm = $data["xzm"];
	$xzone = $data["xzone"];
	$xdisctype = $data["xdisctype"];
	$xopincapply = $data["xopincapply"];
	$xdepositnum = $data["xdepositnum"];


	//,xtotamt,xdisctype

	$sql2 = "select isnull(xwh, '') xwh from xcodes where zid='$zid' and xcode='$xterritory' and xtype='Territory' and zactive='1'";
	$stmt2 = sqlsrv_query($conn, $sql2);

	if ($stmt2 === false) {
    die(print_r(sqlsrv_errors(), true));
	}
	$xwh = '';
	while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
    $xwh = $row['xwh'];
    //echo "xwh: " . $xwh . "<br>";
	}

	sqlsrv_free_stmt($stmt2);

	//pdmst.xstaff("xposition='"+#position+"'")
	$sql3 = "select xzm from executiveview where zid ='$zid' and xtso ='$xtso' ";
	$stmt3 = sqlsrv_query($conn, $sql3);

	if ($stmt3 === false) {
    die(print_r(sqlsrv_errors(), true));
	}
	$xzm = '';

	while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
    $xzm = $row['xzm'];
    //echo "xzm: " . $xzm . "<br>";
	}

	sqlsrv_free_stmt($stmt3);


	$sql= "	INSERT INTO opsoheader (ztime, zauserid, zid, xsonumber, xdate,  xlong,	xref, xfwh,  xstatusso, xdornum, xcus,
	 xdiscamt,   xtso, xdm,xdatedel, xdivision, xstatusdo, xstatus,xpreparer,  xshift, xterritory,   xpnature, xzm, xzone, xstatusclose 
	 ,xtotamt,xdisctype,xopincapply, xidsup, xdepositnum)

	VALUES (getdate(), '$zauserid', '$zid', '$xtornum', '$xdate',  '','' , '$xwh' , 1, '', '$xcus', 
	 0.00,  '$xtso', '$xdm',  getdate(), '$xdivision', 1, 1, '$xpreparer', '',  '$xterritory',  '$xpnature',  '$xzm', '$xzone', 1,
	 '$xtotamt',  '$xdisctype', '$xopincapply', '', '$xdepositnum')";


	
	if (sqlsrv_query($conn, $sql)) {
		echo '{"result" : "Data Inserted"}';
		// code...
	} else{
        die( print_r( sqlsrv_errors(), true) );
	}


}




?> 
