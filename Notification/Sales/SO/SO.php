<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT
xsonumber,
ISNULL(REPLACE(CONVERT(VARCHAR, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE xcus = opsoheader.xcus AND zid = opsoheader.zid), '') AS cusname,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = opsoheader.zid AND xnum = opsoheader.xstatus), '') AS statusName,
ISNULL(xterritory, '') AS xterritory,
ISNULL(xfwh, '') AS xfwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = opsoheader.zid AND xcode = opsoheader.xfwh), '-') AS xwhdesc,
ISNULL(xref, '') AS xref,
ISNULL(xdepositnum, '') AS 	xdepositnum,
ISNULL(xopincapply, '') AS xopincapply,
ISNULL(xdisctype, '') AS 	xdisctype,
ISNULL(xpnature, '') AS xpnature,
ISNULL(xdeliloc, '') AS xdeliloc,
ISNULL((SELECT xname FROM pdmst WHERE zid = opsoheader.zid AND xstaff = opsoheader.xtso), '') AS tsoName,
'Executive: ' + ISNULL((SELECT xname FROM pdmst WHERE zid = opsoheader.zid AND xstaff = opsoheader.xtso AND zactive = 1), '') + ', ' +
'ZM: ' + ISNULL((SELECT xname FROM pdmst WHERE zid = opsoheader.zid AND xstaff = opsoheader.xzm AND zactive = 1), '') + ', ' +
'DSM: ' + ISNULL((SELECT xname FROM pdmst WHERE zid = opsoheader.zid AND xstaff = opsoheader.xdm AND zactive = 1), '') AS xtsoname,
'Base: ' + ISNULL(xterritory, '') + ', ' +
'Zone: ' + ISNULL(xzone, '') + ', ' +
'Division: ' + ISNULL(xdivision, '') AS location
FROM opsoheader
WHERE xstatus IN ('2', '3')
AND LEFT(xsonumber, 2) = 'SO'
AND xidsup = ?
AND zid = ?
ORDER BY xsonumber DESC";

//(select xlong from zstatus where xnum = poreqheader.xstatusreq and zid = poreqheader.zid)

$params = [ $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
