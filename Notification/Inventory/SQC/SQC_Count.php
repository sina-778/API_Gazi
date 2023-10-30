<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT count(xtornum) as total
FROM imtorheader 
WHERE 
zid = $zid 
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') 
AND LEFT(xtornum, 2) = 'RR' 
AND xstatustor NOT IN ('4','11','18','6','7','') ";

$params = [ $zid, $xposition, $xposition, $xposition];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    // Fetch only one row using fetch(PDO::FETCH_ASSOC)
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($row);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}

