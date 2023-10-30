<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT count(xbomkey) as total
FROM bmbomheader
WHERE 
LEFT(xbomkey, 4) = 'BM--'
AND xstatus NOT IN ('4', '', '6', '7')
AND zid = ?
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?)";

$params = [$zid ,$xposition, $xposition, $xposition ];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Fetch only one row using fetch(PDO::FETCH_ASSOC)
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}

