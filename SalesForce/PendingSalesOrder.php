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

  
    //$xtypeleave=$data["xtypeleave"];

	// $sql = "select xtotamt,xnote1,xlong,xtotamt,xterritory,xtso,(select xname from cappo where xsp=imtorheader.xtso)xtsoname,xstatustor,xref,(select xorg from cacus where xcus=imtorheader.xcus)cusname,(select xlong from xcodes where xtype='Branch' and xcode=imtorheader.xtwh)xtbrname,xstatustor,xsubcat,xwh,xfwh,xfproject,xdatedel,xdate,xterritory,xtornum,(select xlong from branchview where zid=imtorheader.zid and xcode=imtorheader.xfwh)fwhdesc
	// ,(select xname from pdmst where xstaff=xpreparer) preparer,(select xdesignation from pdmst where xstaff=xpreparer) designation,
	// 		   (select xdeptname from pdmst where xstaff=xpreparer) deptname,(select xname from pdmst where xstaff=xsignatory1) signname
	// 		   ,(select xdesignation from pdmst where xstaff=xsignatory1) signdesignation,
	// 		   (select xdeptname from pdmst where xstaff=xsignatory1) signdeptname
	// from imtorheader where xstatustor in  ('Applied','Recommended')  and xidsup=$xposition and left(xtornum,2)='SO' order by xtornum";
	$sql ="	select zid,(select zorg from zbusiness where zid= opsoheader.zid) org,xidsup,xtotamt,xnote1,xlong,xtotamt,xterritory,xtso,(select xname from cappo where xsp=opsoheader.xtso and zid= opsoheader.zid)xtsoname,xsonumber,
	(select xorg from cacus where xcus=opsoheader.xcus and zid = opsoheader.zid)cusname,xstatusso,ISNULL(REPLACE(convert(varchar, xdatedel, 3),'0000-00-00',''),'-') AS xdatedel,ISNULL(REPLACE(convert(varchar, xdate, 3),'0000-00-00',''),'-') AS xdate,xterritory,xsonumber
	from opsoheader where xstatusso in  ('Applied','Recommended')  and xidsup='$xposition' and left(xsonumber,2)='SO'";


    
    $stmt = sqlsrv_query( $conn, $sql );

	if( $stmt === false) {
		die( print_r( sqlsrv_errors(), true) );
	}

	$rows = array();


    while( $r = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        //echo $row['LastName'].", ".$row['FirstName']."<br />";
        $rows [] = $r;
      //   echo json_encode($rows);
    }

    echo json_encode($rows);

    sqlsrv_free_stmt($stmt);

}

?> 