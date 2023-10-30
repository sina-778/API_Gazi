<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xsonumber = $data['xsonumber'];

$sql =  "SELECT
zid,
ISNULL(xsonumber, '') AS xsonumber,
ISNULL(xfwh, '') AS xfwh,
ISNULL(xitem, '') AS xitem,
(select isnull(xdesc,'') xdesc from caitem where xitem = opsodetailview.xitem and zid = opsodetailview.zid) name,
ISNULL(xqtyreq, 0) AS xqtyreq,
ISNULL(xdphqty, 0) AS xdphqty,
ISNULL(xqtypor, '') AS xqtypor,
ISNULL(xrate, 0) AS xrate,
ISNULL(xdisc, 0) AS xdisc,
ISNULL(xdiscamt, 0) AS xdiscamt,
ISNULL(xlineamt, 0) AS xlineamt,
ISNULL(xpartno, '') AS xpartno
FROM opsodetailview
WHERE xsonumber =  ? AND zid = ?
";

$params = [$xsonumber, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
