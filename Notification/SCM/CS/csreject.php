<?php

header('content-type: application/json');

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        postmethod($data);
        break;
    default:
        echo '{"name": "data not found"}';
        break;
}

function postMethod($requestData)
{
    require_once '../../index.php';

    $zid = $requestData['zid'];
    $user = $requestData['user'];
    $xposition = $requestData['xposition'];
    $xporeqnum = $requestData['xporeqnum'];
    $ypd = 0;
    $xnote = $requestData['xnote'];
    $approvalType = 'CS';

    $sql = "UPDATE poreqheader SET xnote1 = :xnote WHERE xporeqnum = :xporeqnum";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':xnote', $xnote, PDO::PARAM_STR);
        $stmt->bindParam(':xporeqnum', $xporeqnum, PDO::PARAM_STR);
        $stmt->execute();

        $sql1 = "EXEC zabsp_Reject_Request ?, ?, ?, ?, ?, ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(1, $zid, PDO::PARAM_INT);
        $stmt1->bindParam(2, $user, PDO::PARAM_STR);
        $stmt1->bindParam(3, $xposition, PDO::PARAM_STR);
        $stmt1->bindParam(4, $ypd, PDO::PARAM_INT);
        $stmt1->bindParam(5, $xporeqnum, PDO::PARAM_STR);
        $stmt1->bindParam(6, $approvalType, PDO::PARAM_STR);
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
