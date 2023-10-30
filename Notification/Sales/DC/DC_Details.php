<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xdornum = $data['xdornum'];

$sql =  "SELECT
ISNULL(xrow, '') AS xrow,
ISNULL(xitem, '') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = opdodetail.zid AND xitem = opdodetail.xitem), '') AS xdesc,
ISNULL(xqtyord, 0) AS xqtyord,
ISNULL(xunit, '') AS xunit,
ISNULL(xrate, 0) AS xrate,
ISNULL(xlineamt, 0) AS xlineamt,
ISNULL(xvatamt, 0) AS xvatamt,
ISNULL(xnetamt, 0) AS xnetamt,
ISNULL(xdiscdet, 0) AS xdiscdet,
ISNULL(xdiscdetamt, 0) AS xdiscdetamt
FROM
opdodetail
WHERE xdornum=? AND zid = ? AND
ISNULL(xpartno, '') <> 'Yes' AND ISNULL(xnote1, '') <> 'Gift Item'
";

$params = [$xdornum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
