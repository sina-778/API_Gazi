<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xgrnnum=$data["xgrnnum"];

$sql =  "SELECT xgrnnum,
xrow,
ISNULL(xunitpur, '') AS xunitpur,
ISNULL(xitem, '') AS  xitem,
(SELECT xdesc FROM caitem WHERE zid = pogrndetail.zid AND xitem = pogrndetail.xitem) AS descr,
ISNULL(xcpoqty, 0) AS xcpoqty,
ISNULL(xqtygrn, 0) AS xqtygrn,
ISNULL(xqtybonus, 0) AS xqtybonus,
ISNULL(xdocrow, 0) AS xdocrow,
ISNULL(xlong, '') AS  xlong
FROM pogrndetail
WHERE xgrnnum = ? and zid = ?;
";


$params = [ $xgrnnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
