<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "select 
zid,
ISNULL(xrow, '') AS xrow,
ISNULL(REPLACE(CONVERT(VARCHAR, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xfstaff, '') AS xfstaff,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = caexecutivechange.xfstaff AND zid = caexecutivechange.zid ), '') AS tstaff,
ISNULL(xstaff, '') AS xstaff,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = caexecutivechange.xstaff AND zid = caexecutivechange.zid ), '') AS staff,
ISNULL(xtype, '') AS xtype,
ISNULL(REPLACE(CONVERT(VARCHAR, xpfeffdate, 3), '0000-00-00', ''), '-') AS xpfeffdate,
ISNULL(xterritory, '') AS xterritory,
ISNULL(xzone, '') AS xzone,
ISNULL(xdivision, '') AS xdivision,
ISNULL(xstatus, '') AS xstatus,
ISNULL((select xlong from zstatus where xnum = caexecutivechange.xstatus and zid = caexecutivechange.zid ), '') AS status
from caexecutivechange
where xstatus in ('2','3')
AND zid = $zid
AND (xidsup = '$xposition') 
ORDER BY xrow desc ";

//(select xlong from zstatus where xnum = poreqheader.xstatusreq and zid = poreqheader.zid)

$params = [$xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
