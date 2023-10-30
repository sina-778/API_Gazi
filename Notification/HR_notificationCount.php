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
		$xstaff=$data["xstaff"];

		$counts = array();
	
		// Early Count
		$sql = "SELECT COUNT(xposition) AS total
				FROM pdattview
				WHERE xstaff = '$xstaff'
					AND xstatusel IN ('WFA', 'Recommended')
					AND xempearly = '1'
					AND xstatustask <> 'Closed'
					AND xdate >= DATEADD(DAY, -45, GETDATE())";
		$stmt = sqlsrv_query($conn, $sql);
		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$counts["earlyCount"] = $row["total"];
		}
	
		// Late Count
		$sql = "SELECT COUNT(xposition) AS total
				FROM pdattview
				WHERE xstaff = '$xstaff'
					AND xstatuslate IN ('WFA', 'Recommended')
					AND xemplate = '1'
					AND xstatustask <> 'Closed'
					AND xdate >= DATEADD(DAY, -45, GETDATE())";
		$stmt = sqlsrv_query($conn, $sql);
		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$counts["lateCount"] = $row["total"];
		}
	
		// Absent Count
		$sql = "SELECT COUNT(xdate) AS total
				FROM pdempat
				WHERE xposition = '$xposition'
					AND zid = '$zid'
					AND xstatus = 'Absent'
					AND xdate >= DATEADD(DAY, -45, GETDATE())";
		$stmt = sqlsrv_query($conn, $sql);
		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$counts["absentCount"] = $row["total"];
		}
	
		// Leave Count
		$sql = "SELECT COUNT(xdate) AS total
				FROM pdleavehdview
				WHERE xposition = '$xposition'
					AND xdate >= DATEADD(DAY, -45, GETDATE())";
		$stmt = sqlsrv_query($conn, $sql);
		if ($stmt === false) {
			die(print_r(sqlsrv_errors(), true));
		}
		if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$counts["leaveCount"] = $row["total"];
		}
	
		// Total Count
		$totalCount = array_sum($counts);
		$counts["total"] = $totalCount;
	
		return $counts;
	}
	

?> 