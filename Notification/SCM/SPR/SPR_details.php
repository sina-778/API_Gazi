<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xtornum = $data['xtornum'];

$sql =  "SELECT 
ISNULL(xrow, '') AS xrow,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = imtordetailview.zid AND xitem = imtordetailview.xitem), '') AS product_Name,
ISNULL(xqtyreq, 0) AS    xqtyreq, 
ISNULL(xavail, 0) AS xavail ,
ISNULL(xunit, '') AS xunit
FROM imtordetailview
WHERE xtornum = ?
AND zid =  ? 
ORDER BY xtornum,xrow ";

$params = [$xtornum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
