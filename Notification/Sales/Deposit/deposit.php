<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT
zid,
ISNULL(xdepositnum, '') AS xdepositnum,
ISNULL(xdepositref, '') AS xdepositref,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE xcus = ardeposit.xcus AND zid = ardeposit.zid), '') AS cusname,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = ardeposit.zid AND xnum = ardeposit.xstatus), '') AS statusName,
ISNULL((SELECT xname FROM cabank WHERE xbank = ardeposit.xbank AND zid = ardeposit.zid), '') AS xbank,
ISNULL(xbranch, '') AS xbranch,
ISNULL(xamount, 0) AS xamount,
ISNULL(xarnature, '') AS xarnature,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = ardeposit.zid AND xnum = ardeposit.xstatusjv), '') AS statusjv,
ISNULL(xnote, '') AS xnote,
ISNULL(xtso, '') AS xtso,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = ardeposit.xtso AND zid = ardeposit.zid AND zactive = '1'), '-') AS Executive,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = ardeposit.xzm AND zid = ardeposit.zid AND zactive = '1'), '-') AS ZM,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = ardeposit.xdm AND zid = ardeposit.zid AND zactive = '1'), '-') AS DSM,
ISNULL(xterritory, '') AS Base,
ISNULL(xzone, '') AS Zone,
ISNULL(xdivision, '') AS Division,
ISNULL(xpreparer, '') AS xpreparer,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = ardeposit.zid AND xstaff = ardeposit.xpreparer), '') AS preparer_name
FROM ardeposit
WHERE LEFT(xdepositnum, 4) = 'DP--' AND xstatus NOT IN ('4', '')
AND zid = $zid
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
ORDER BY xdepositnum DESC";

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
