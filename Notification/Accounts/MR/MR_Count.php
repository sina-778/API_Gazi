<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT count(xvoucher) as total
FROM arhed
WHERE xprime > 0 AND left(xvoucher, 2) = 'MR' AND xstatusmr IN ('2', '3')
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?) 
AND zid = ?
";

$params = [$xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Fetch only one row using fetch(PDO::FETCH_ASSOC)
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}

