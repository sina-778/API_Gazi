<?php

header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        $requestData = json_decode(file_get_contents('php://input'), true);
        postMethod($requestData);
        break;
    default:
        echo '{"error": "Method not allowed"}';
        break;
}

function postMethod($requestData)
{
    require_once '../../index.php';

    $zid = $requestData['zid'];
    $user = $requestData['user'];
    $xposition = $requestData['xposition'];
    $xdepositnum = $requestData['xdepositnum'];
    $xbank = $requestData['xbank'];
    $xbranch = $requestData['xbranch'];
    $ypd = 0;
    $xstatusreq = $requestData['xstatusreq'];
    $approvalType = 'Deposit Approval';

    // set temp =#sesql("update ardeposit set xbank ='"+xbank+"',xbranch ='"+xbranch+"' where zid='"+#id+"' and xdepositnum='"+xdepositnum+"'")
    // set temp = #spsql(zabsp_apvprcs,#id,#user,#position,xdepositnum,0,xstatus,"Deposit Approval")

    $sql = "UPDATE ardeposit SET xbank = :xbank, xbranch = :xbranch WHERE xdepositnum = :xdepositnum and zid = :zid ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':xbank', $xbank, PDO::PARAM_STR);
        $stmt->bindParam(':xbranch', $xbranch, PDO::PARAM_STR);
        $stmt->bindParam(':xdepositnum', $xdepositnum, PDO::PARAM_STR);
        $stmt->bindParam(':zid', $zid, PDO::PARAM_STR);
        $stmt->execute();

        $sql1 = "EXEC zabsp_apvprcs ?, ?, ?, ?, ?, ?, ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(1, $zid, PDO::PARAM_INT);
        $stmt1->bindParam(2, $user, PDO::PARAM_STR);
        $stmt1->bindParam(3, $xposition, PDO::PARAM_STR);
        $stmt1->bindParam(4, $xdepositnum, PDO::PARAM_STR);
        $stmt1->bindParam(5, $ypd, PDO::PARAM_INT);
        $stmt1->bindParam(6, $xstatusreq, PDO::PARAM_STR);
        $stmt1->bindParam(7, $approvalType, PDO::PARAM_STR);
        $stmt1->execute();

        if ($stmt1) {
            $rows = array();
            while ($r = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $r;
            }
            echo json_encode($rows);
        } else {
            echo '{"error": "Failed to execute statement"}';
        }
    } catch (PDOException $e) {
        echo '{"error": "' . $e->getMessage() . '"}';
    }
}

?>
