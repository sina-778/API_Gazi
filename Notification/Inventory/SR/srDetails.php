<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xtornum = $data['xtornum'];

$sql =  "SELECT 
xrow,
xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = imtordetail.zid AND xitem = imtordetail.xitem), '') AS xdesc,
ISNULL(xprepqty, 0) AS xqtyreq,
ISNULL(xnote, '') AS xdphqty, 
ISNULL(xunit, '') AS xunit
FROM imtordetail
WHERE xtornum = ? AND zid = ?
ORDER BY xrow;
";

$params = [$xtornum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
