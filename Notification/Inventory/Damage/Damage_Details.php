<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xtornum=$data["xtornum"];

$sql =  "SELECT 
xrow,
ISNULL(xunit, '-') AS xunit,
ISNULL(xitem, '-') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = imtordetail.xitem AND zid = imtordetail.zid), '') AS product_Name,
ISNULL(xprepqty, 0) AS xprepqty,
ISNULL(xqtyreq, 0) AS xdphqty,
ISNULL(xnote, '-') AS xnote,
ISNULL(xbrand, '') AS xbrand,
ISNULL(xitemconv, '-') AS xitemconv,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = imtordetail.xitemconv AND zid = imtordetail.zid), '') AS conv_product_Name,
ISNULL(xqtylead, 0) AS xqtylead,
ISNULL((SELECT xunit FROM caitemview WHERE xitem = imtordetail.xitemconv AND zid = imtordetail.zid), '') AS conv_unit,
ISNULL((SELECT SUM(xprepqty) FROM imtordetail WHERE xtornum = ? AND zid = ? ), 0) AS damageQty
FROM imtordetail
WHERE xtornum = ? AND zid = ? 
ORDER BY xrow ";


$params = [ $xtornum, $zid, $xtornum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
