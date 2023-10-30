<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xporeqnum = $data['xporeqnum'];

$sql =  "SELECT
ISNULL(xrow, 0) AS xrow,
ISNULL(xgrnnum, '') AS xgrnnum,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = poreqdetail.zid AND xitem = poreqdetail.xitem), '') AS xdesc,
ISNULL(xlineamt, 0) AS xlineamt
FROM
poreqdetail
WHERE xporeqnum = ? AND zid = ?
ORDER BY xrow
";

$params = [$xporeqnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
