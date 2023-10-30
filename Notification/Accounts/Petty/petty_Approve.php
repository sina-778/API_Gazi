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
    $xbillno=$requestData["xbillno"];
    $ypd = 0;
    $xstatus=$requestData["xstatus"];
    $approvalType = 'PCR Approval';

    $sql = "EXEC zabsp_apvprcs ?, ?, ?, ?, ?, ?, ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $zid, PDO::PARAM_INT);
        $stmt->bindParam(2, $user, PDO::PARAM_STR);
        $stmt->bindParam(3, $xposition, PDO::PARAM_STR);
        $stmt->bindParam(4, $xbillno, PDO::PARAM_STR);
        $stmt->bindParam(5, $ypd, PDO::PARAM_INT);
        $stmt->bindParam(6, $xstatus, PDO::PARAM_STR);
        $stmt->bindParam(7, $approvalType, PDO::PARAM_STR);
        $stmt->execute();

        echo '{"result": "Data inserted"}';
    } catch (PDOException $e) {
        echo '{"error": "' . $e->getMessage() . '"}';
    }
}

?>
