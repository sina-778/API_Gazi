<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xporeqnum = $data['xporeqnum'];

$sql =  "SELECT 
ISNULL(xrow, '') AS xrow,
ISNULL(xitem, '') AS xitem,
ISNULL(xdesc, '') AS xdesc,
ISNULL(xspecification, '') AS xspecification,
ISNULL(xqtypur, 0) AS xqtypur,
ISNULL(xunitpur, '') AS xunitpur,
ISNULL(xrate, 0) AS xrate,
ISNULL(xlineamt, 0) AS xlineamt,
ISNULL((xrategrn * xqtypur), 0) AS povalue,
ISNULL(xrategrn, 0) AS xrategrn,
ISNULL(xspecification, '') AS xspecification
FROM poorddetailview
WHERE xpornum = ? AND zid = ?
ORDER BY xrow";

$params = [$xporeqnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
