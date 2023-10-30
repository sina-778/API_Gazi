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

// function postMethod($requestData)
// {
//     require_once '../../index.php';

//     $zid = $requestData['zid'];
//     $user = $requestData['user'];
//     $xposition = $requestData['xposition'];
//     $xbatch=$requestData["xbatch"];
//     $ypd = 0;
//     $xstatus=$requestData["xstatus"];
//     $approvalType = 'Batch Approval';

//     $sql = "EXEC zabsp_apvprcs ?, ?, ?, ?, ?, ?, ?";

//     try {
//         $stmt = $conn->prepare($sql);
//         $stmt->bindParam(1, $zid, PDO::PARAM_INT);
//         $stmt->bindParam(2, $user, PDO::PARAM_STR);
//         $stmt->bindParam(3, $xposition, PDO::PARAM_STR);
//         $stmt->bindParam(4, $xbatch, PDO::PARAM_STR);
//         $stmt->bindParam(5, $ypd, PDO::PARAM_INT);
//         $stmt->bindParam(6, $xstatus, PDO::PARAM_STR);
//         $stmt->bindParam(7, $approvalType, PDO::PARAM_STR);
//         $stmt->execute();

//         echo '{"result": "Data inserted"}';
//     } catch (PDOException $e) {
//         echo '{"error": "' . $e->getMessage() . '"}';
//     }
// }

function postMethod($requestData)
{
    require_once '../../index.php';

    $zid = $requestData['zid'];
    $user = $requestData['user'];
    $xposition = $requestData['xposition'];
    $xbatch = $requestData["xbatch"];
    $ypd = 0;
    $xstatus = $requestData["xstatus"];
    $approvalType = 'Batch Approval';

    $sql = "EXEC zabsp_apvprcs ?, ?, ?, ?, ?, ?, ?";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $zid, PDO::PARAM_INT);
        $stmt->bindParam(2, $user, PDO::PARAM_STR);
        $stmt->bindParam(3, $xposition, PDO::PARAM_STR);
        $stmt->bindParam(4, $xbatch, PDO::PARAM_STR);
        $stmt->bindParam(5, $ypd, PDO::PARAM_INT);
        $stmt->bindParam(6, $xstatus, PDO::PARAM_STR);
        $stmt->bindParam(7, $approvalType, PDO::PARAM_STR);
        $stmt->execute();

        // Check if xstatus is 4
        if ($xstatus == "4") {
            $sql2 = "EXEC zabsp_MO_processProduction ?, ?, ?, ?, ?, ?, ?";
            $xqtyprd = $requestData["xqtyprd"];
            $xwastqty = $requestData["xwastqty"];

            $stmt2 = $conn->prepare($sql2);
            $stmt2->bindParam(1, $zid, PDO::PARAM_INT);
            $stmt2->bindParam(2, $user, PDO::PARAM_STR);
            $stmt2->bindParam(3, $xbatch, PDO::PARAM_STR);
            $stmt2->bindParam(4, $xqtyprd, PDO::PARAM_INT);
            $stmt2->bindParam(5, $xwastqty, PDO::PARAM_INT);
            $stmt2->bindParam(6, "Process", PDO::PARAM_STR);
            $stmt2->bindParam(7, $xposition, PDO::PARAM_STR);
            $stmt2->execute();
        }

        echo '{"result": "Data inserted"}';
    } catch (PDOException $e) {
        echo '{"error": "' . $e->getMessage() . '"}';
    }
}


?>
