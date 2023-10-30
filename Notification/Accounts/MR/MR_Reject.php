<?php

header('content-type: application/json');

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        postMethod($data);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("message" => "Invalid request method"));
        break;
}

function postMethod($requestData)
{
    require_once '../../index.php';

    $zid = $requestData['zid'];
    $user = $requestData['user'];
    $xposition = $requestData['xposition'];
    $xvoucher = $requestData["xvoucher"];
    $ypd = 0;
    $xnote = $requestData["xnote"];
    $approvalType = 'MR';

    $sql = "UPDATE arhed set xnote1 = :xnote WHERE xvoucher = :xvoucher and zid = :zid";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':xnote', $xnote, PDO::PARAM_STR);
        $stmt->bindParam(':xvoucher', $xvoucher, PDO::PARAM_STR);
        $stmt->bindParam(':zid', $zid, PDO::PARAM_INT);
        $stmt->execute();

        $sql1 = "EXEC zabsp_Reject_Request ?, ?, ?, ?, ?, ?";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(1, $zid, PDO::PARAM_INT);
        $stmt1->bindParam(2, $user, PDO::PARAM_STR);
        $stmt1->bindParam(3, $xposition, PDO::PARAM_STR);
        $stmt1->bindParam(4, $ypd, PDO::PARAM_INT);
        $stmt1->bindParam(5, $xvoucher, PDO::PARAM_STR);
        $stmt1->bindParam(6, $approvalType, PDO::PARAM_STR);
        $stmt1->execute();

        $rows = $stmt1->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            http_response_code(204); // No Content
        } else {
            echo json_encode($rows);
        }
    } catch (PDOException $e) {
        // Suppress the error message for successful execution with no rows returned
        if ($e->getCode() !== 'IMSSP') {
            http_response_code(500);
            echo json_encode(array("error" => "Failed to execute statement"));
        }
    }
}
