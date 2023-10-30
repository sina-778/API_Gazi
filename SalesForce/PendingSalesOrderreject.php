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




// function postmethod($data){

//     include "index.php";

    
//     $zid=$data["zid"];
//     $user=$data["user"];
//     $xposition=$data["xposition"];
//     $xtornum=$data["xtornum"];
//     $wh = $data["wh"];
//     $xnote1 = $data["xnote1"];



//     $sql = "Update opsoheader set xnote1='$xnote1' where xsonumber='$xtornum' and zid = '$zid' and xsonumber = '$xtornum' ";

//     $stmt = sqlsrv_query( $conn, $sql );


// 	$sql1 = "zabsp_Reject_Request $zid,'$user','$xposition','$wh','$xtornum','SO'";
 
 
//     // set temp =#sesql("update opsoheader set xnote1 ='"+xnote1+"' where zid='"+#id+"' and xsonumber='"+xsonumber+"'")
//     // set temp =  #spsql(zabsp_Reject_Request,#id,#user,#position,0,xsonumber,"SO")

  

//     $stmt = sqlsrv_query( $conn, $sql1 );

//    //https://stackoverflow.com/questions/10637521/stored-procedure-only-partially-executes-from-php 

// 	if( $stmt === false) {
// 		die( print_r( sqlsrv_errors(), true) );
// 	}

// 	$rows = array();


//     while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

//         $rows [] = $r;

//     }

//     echo json_encode($rows);

//     sqlsrv_free_stmt($stmt);

// }

function postmethod($data){
    include "index.php";
    
    $zid = $data["zid"];
    $user = $data["user"];
    $xposition = $data["xposition"];
    $xtornum = $data["xtornum"];
    $wh = $data["wh"];
    $xnote1 = $data["xnote1"];

    // Update the xnote1 field in opsoheader table
    $sql = "UPDATE opsoheader SET xnote1 = ? WHERE xsonumber = ? AND zid = ? AND xsonumber = ?";
    $params = array($xnote1, $xtornum, $zid, $xtornum);
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(json_encode(array("message" => "Failed to update xnote1 field")));
    }
    sqlsrv_free_stmt($stmt);

    // Call the stored procedure to reject SO
    $sql1 = "{CALL zabsp_Reject_Request(?, ?, ?, ?, ?, ?)}";
    $params1 = array($zid, $user, $xposition, 0, $xtornum, "SO");
    $stmt1 = sqlsrv_query($conn, $sql1, $params1);

    if ($stmt1 === false) {
        die(json_encode(array("message" => "Failed to call stored procedure")));
    }

    $rows = array();

    while ($r = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }

    // Check if the SO is rejected or not
    if (count($rows) > 0 && $rows[0]["Result"] == "Rejected") {
        echo json_encode(array("message" => "SO Rejected Successfully"));
    } else {
        echo json_encode(array("message" => "Failed to reject SO"));
    }

    sqlsrv_free_stmt($stmt1);
}

?> 