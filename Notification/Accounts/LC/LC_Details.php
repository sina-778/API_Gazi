<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xreqnum = $data['xreqnum'];

$sql =  "SELECT
ISNULL(xrow, '') AS xrow,
ISNULL(xtrn, '') AS xtrn,
ISNULL(xaccdr, 0) AS xaccdr,
ISNULL((select xdesc from acmst where acmst.zid=lcopdutycostview.zid and acmst.xacc=lcopdutycostview.xaccdr), '') AS descdr,
ISNULL(xacccr, '') AS xacccr,
ISNULL((select xdesc from acmst where acmst.zid=lcopdutycostview.zid and acmst.xacc=lcopdutycostview.xacccr), '') AS desccdr,
ISNULL(xprime, 0) AS xprime,
ISNULL((select xlong from zstatus where zid=lcopdutycostview.zid and xnum=lcopdutycostview.xapstatus1), '') AS xstatusapdesc,
ISNULL(xreqnum, '') AS xreqnum
FROM
lcopdutycostview
WHERE xreqnum=? AND zid = ? 
ORDER BY xrow
";

$params = [$xreqnum, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
