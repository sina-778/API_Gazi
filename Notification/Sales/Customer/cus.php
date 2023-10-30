<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "select 
zid,
ISNULL(xcus, '') AS xcus,
ISNULL(xorg, '') AS xorg,
ISNULL(xmadd, '') AS xmadd,
ISNULL(xcontact, '') AS xcontact,
ISNULL(xdesignation, '') AS xdesignation,
ISNULL(xphone, '') AS xphone,
ISNULL(xemail, '') AS xemail,
ISNULL(xgcus, '') AS xgcus,
ISNULL(xtso, '') AS xtso,
ISNULL(xterritory, '') AS xterritory,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = cacus.xtso AND zid = cacus.zid ), '') AS xtsoname,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = cacus.xzm AND zid = cacus.zid), '') AS ZM,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = cacus.xdm AND zid = cacus.zid), '') AS DSM,
ISNULL(xzone, '') AS xzone,
ISNULL(xdivision, '') AS Division,
ISNULL((SELECT xzone FROM xcodes WHERE   zid = cacus.zid AND xcode = cacus.xterritory and xtype='Territory' AND zactive = 1), '') AS Zone,
ISNULL((SELECT xdivision FROM xcodes WHERE   zid = cacus.zid AND xcode = cacus.xterritory and xtype='Territory' AND zactive = 1), '') AS Division,
ISNULL(xdistrict, '') AS xdistrict,
ISNULL(xthana, '') AS xthana,
ISNULL(xstatus, '') AS xstatus,
ISNULL((select xlong from zstatus where xnum = cacus.xstatus and zid = cacus.zid ), '') AS xstatusdesc
from cacus
where xstatus not in ('5','4','1')
AND xtype='Customer'
AND zid = $zid
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
Order by xcus desc ";

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
