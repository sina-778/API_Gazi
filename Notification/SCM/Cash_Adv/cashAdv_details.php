<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xporeqnum = $data['xporeqnum'];

$sql =  "SELECT
ISNULL(xrow, '') AS xrow,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = poreqdetail.zid AND xitem = poreqdetail.xitem), '') AS xdesc,
ISNULL(xqtyreq, 0) AS xqtyreq,
ISNULL(xqtyapv, 0) AS xqtyapv,
ISNULL(xunitpur, '') AS xunitpur,
ISNULL(xrate, 0) AS xrate,
ISNULL(xlineamt, 0) AS xlineamt,
ISNULL(xspecification, '') AS xspecification
FROM poreqdetail
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
