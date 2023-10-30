<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xdocnum=$data["xdocnum"];

$sql =  "SELECT 
ISNULL(xrow, 0) AS xrow,
ISNULL(xunit, '-') AS xunit,
ISNULL(xitem, '-') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = imdcdetail.xitem AND zid = imdcdetail.zid), '-') AS descp,
ISNULL(xqtyord, 0) AS xqtyord,
ISNULL(xnote, '-') AS xnote
FROM imdcdetail 
WHERE 
xdocnum = ?
AND zid = ?
";


$params = [ $xdocnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
