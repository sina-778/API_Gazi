<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xcrnnum = $data['xcrnnum'];

$sql =  "SELECT 
ISNULL(xrow, 0) AS xrow,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE caitem.zid = opcrndetail.zid AND caitem.xitem = opcrndetail.xitem), '') AS xdesc,
ISNULL(xqtyord, 0) AS xqtyord,
ISNULL(xrate, 0) AS xrate,
ISNULL(xcost, 0) AS xcost,
ISNULL(xlineamt, 0) AS xlineamt
FROM 
opcrndetail
WHERE 
xcrnnum = ? AND zid = ?
ORDER BY xrow;
";

$params = [$xcrnnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
