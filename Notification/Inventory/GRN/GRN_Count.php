<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT count(xgrnnum) as total
FROM pogrnheader
WHERE 
(xsuperiorsp = ? OR xsuperior2 = ? OR xsuperior3 = ?) 
AND zid = ? AND  xstatusgrn = '1' 
AND LEFT(xgrnnum, 3) = 'GRN' 
AND xstatusdoc NOT IN ('1', '4', '7') ";

$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Fetch only one row using fetch(PDO::FETCH_ASSOC)
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}

