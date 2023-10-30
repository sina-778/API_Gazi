<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xvoucher=$data["xvoucher"];

$sql =  "SELECT xrow, xvoucher,
ISNULL(xacc, '') AS xacc,
(SELECT  ISNULL(xdesc,'')  FROM acmst WHERE acmst.zid = acdetail.zid AND acmst.xacc = acdetail.xacc) AS xdesc,
ISNULL(xsub, '') AS xsub,
ISNULL((SELECT ISNULL(xorg,'') FROM acsubview WHERE zid = acdetail.zid AND xsub = acdetail.xsub AND xacc = acdetail.xacc),'') AS subdesc,
ISNULL(xdebit, 0) AS xdebit,
ISNULL(xcredit, 0) AS xcredit
FROM acdetail
WHERE xvoucher = ? AND zid = ? 
ORDER BY xrow";


$params = [ $xvoucher, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
