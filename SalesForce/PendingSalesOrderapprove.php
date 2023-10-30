<?php

    header('content-type: application/json');
    
        $request = $_SERVER['REQUEST_METHOD'];
    
    
        switch ( $request) {
    
            case 'POST':
                $data=json_decode(file_get_contents('php://input'),true);
                postmethod($data);
            break;
    
            default:
                echo '{"name" : "data not found"}';
            break;
        }
    
    
    function postmethod($data){
    
        include "index.php";
    
        
        $zid=$data["zid"];
        $user=$data["user"];
        $xposition=$data["xposition"];
        $xtornum=$data["xtornum"];
        $ypd= $data["ypd"];
        $xstatustor=$data["xstatustor"];
        
    
        $sql = "zabsp_apvprcs $zid,'$user','$xposition','$xtornum',$ypd,'$xstatustor','SO Approval'";
      //   set temp = #spsql(zabsp_apvprcs,#id,#user,#position,xsonumber,0,xstatusso,"SO Approval")
    
       
         if (sqlsrv_query($conn, $sql)) {
            echo '{"result" : "Data Inserted"}';
    
        } else{
            die( print_r( sqlsrv_errors(), true) );
            //echo '{"result" : "Data Not Inserted"}';
        }
    
    }
    
    ?> 