<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xporeqnum = $data['xporeqnum'];

$sql =  "SELECT 
ISNULL(xrow, '') AS xrow,xqotnum,xporeqnum,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = poquotdetail.zid AND xitem = poquotdetail.xitem), '') AS product_Name,
ISNULL(xunitpur, '') AS    xunitpur, 
ISNULL(xqtyreq, 0) AS xqtyreq ,
ISNULL(xrate, 0) AS xrate,
ISNULL(xratenegotiate, 0) AS xratenegotiate
FROM poquotdetail 
where  xporeqnum=? and zid= ? 
ORDER BY xporeqnum,xrow ";

$params = [$xporeqnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
