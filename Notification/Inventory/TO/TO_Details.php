<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xtornum=$data["xtornum"];

$sql =  "SELECT 
ISNULL(xrow, 0) AS xrow,
ISNULL(xunit, '-') AS xunit,
ISNULL(xitem, '-') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = imtordetail.xitem AND zid = imtordetail.zid), '-') AS product_Name,
ISNULL(xprepqty, 0) AS xprepqty,
ISNULL(xdphqty, 0) AS xdphqty,
ISNULL(xnote, '-') AS xnote
FROM imtordetail 
WHERE xtornum = ? AND zid = ? 
ORDER BY xrow;
";


$params = [ $xtornum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
