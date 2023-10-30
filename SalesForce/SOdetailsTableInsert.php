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


	function postmethod($data) {
		include "index.php";
	
		$zid = $data["zid"];
		$zauserid = $data["zauserid"];
		$xtornum = $data["xtornum"];
		$xrow = $data["xrow"];
		$xitem = $data["xitem"];
		$xunit = $data["xunit"];
		$qty = $data["qty"];
		$amount = $data["amount"];
		$xpartno = $data["xpartno"];
		$xmasteritem = $data["xmasteritem"];
		$xrate = $data["xrate"];
		$xvatrate = $data["xvatrate"];
		$xdisc = $data["xdisc"];
		$xdiscamt = $data["xdiscamt"];
		$xdiscad = $data["xdiscad"];
		$xdiscadamt = $data["xdiscadamt"];
		$xnote1 = $data["xnote1"];
	
		$sql = "INSERT INTO opsodetail(ztime, zauserid, zid, xsonumber, xrow, xitem, xqtyord, xunit, xqtyreq, xdphqty, xqtyalc, xlineamt, xpartno, xmasteritem, xrate, xvatrate, xdisc, xdiscamt, xdiscad, xdiscadamt, xnote1) 
				VALUES (GETDATE(), ?, ?, ?, ?, ?, 0, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	
		$params = array(
			$zauserid,
			$zid,
			$xtornum,
			$xrow,
			$xitem,
			$xunit,
			$qty,
			$qty,
			$amount,
			$xpartno,
			$xmasteritem,
			$xrate,
			$xvatrate,
			$xdisc,
			$xdiscamt,
			$xdiscad,
			$xdiscadamt,
			$xnote1
		);
	
		$stmt = sqlsrv_query($conn, $sql, $params);
	
		if ($stmt) {
			echo '{"result": "Data Inserted"}';
		} else {
			die(print_r(sqlsrv_errors(), true));
		}

		
		// set temp3 = #spsql(zabsp_SO_validateAfterDetailSO,#id,#user,xsonumber,xitem,xrow)
		
		

		$sql1 = "{CALL zabsp_SO_validateAfterDetailSO(?, ?, ?, ?, ?)}";
		$params1 = array($zid, $zauserid, $xtornum, $xitem,	$xrow);

		$stmt1 = sqlsrv_query($conn, $sql1, $params1);

		if ($stmt1 === false) {
			die(json_encode(array("message" => "Failed to call stored procedure")));
		} else{
			sqlsrv_free_stmt($stmt1);
		}

		// // // set temp3 = #spsql(zabsp_op_promotion,#id,#user,xsonumber)

		$sql2 = "{CALL zabsp_op_promotion(?, ?, ?)}";
		$params2 = array($zid, $zauserid, $xtornum);

		$stmt2 = sqlsrv_query($conn, $sql2, $params2);

		if ($stmt2 === false) {
			die(json_encode(array("message" => "Failed to call stored procedure")));
		} else{
			sqlsrv_free_stmt($stmt2);
		}


		// $sql3 = "select isnull(xfwh, '') xwh from opsoheader where zid='$zid' and xsonumber='$xtornum'";
		// $stmt3 = sqlsrv_query($conn, $sql3);
	
		// if ($stmt3 === false) {
		// die(print_r(sqlsrv_errors(), true));
		// }
		// $xwh = '';
		// while ($row = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
		// $xwh = $row['xwh'];
		// //echo "xwh: " . $xwh . "<br>";
		// }

		// // // set temp2= #spsql(zab_sp_giftitem,#id,#user,xsonumber,xitem,sodate,xdphqty,xfwh,"Add")

		// $sql4 = "{CALL zab_sp_giftitem(?, ?, ?, ?, ?, ?, ?)}";
		// $params4 = array($zid, $zauserid, $xtornum, $xitem, GETDATE(), $qty, $xwh, "Add");

		// $stmt4 = sqlsrv_query($conn, $sql4, $params4);

		// if ($stmt4 === false) {
		// 	die(json_encode(array("message" => "Failed to call stored procedure")));
		// } else{
		// 	sqlsrv_free_stmt($stmt4);
		// }
		


	}
	




?> 
