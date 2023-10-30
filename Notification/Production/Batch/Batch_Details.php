<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xbatch=$data["xbatch"];

$sql =  "SELECT 
xbomrow,
xbatch,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = modetail.zid AND xitem = modetail.xitem), '') AS xdesc,
ISNULL(xqtyreq, 0) AS xqtyreq,
ISNULL((SELECT xunit FROM caitem WHERE zid = modetail.zid AND xitem = modetail.xitem), '') AS unit,
ISNULL(xavail, 0) AS xavail,
ISNULL((select xlong from zstatus where zid=modetail.zid and xnum=modetail.xtype), '') AS xsstype
FROM modetail
WHERE xbatch = ? AND zid = ?
ORDER BY xrow;";


$params = [ $xbatch, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
