<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT
xcrnnum,
ISNULL(xordernum, '') AS xordernum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xref, '') AS xref,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE xcus = opcrnheader.xcus AND zid = opcrnheader.zid), '-') AS xorg,
ISNULL(xtso, '') AS xtso,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opcrnheader.xtso AND zid = opcrnheader.zid AND zactive = '1'), '-') AS Executive,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opcrnheader.xzm AND zid = opcrnheader.zid AND zactive = '1'), '-') AS ZM,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opcrnheader.xdm AND zid = opcrnheader.zid AND zactive = '1'), '-') AS DSM,
ISNULL(xterritory, '') AS Base,
ISNULL(xzone, '') AS Zone,
ISNULL(xdivision, '') AS Division,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = opcrnheader.zid AND xcode = opcrnheader.xwh), '-') AS xwhdesc,
ISNULL(xpnature, '') AS xpnature,
ISNULL(xtotamt, 0) AS xtotamt,
ISNULL(xstatus, '') AS xstatus,
ISNULL(((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = opcrnheader.zid AND xnum = opcrnheader.xstatus)), '') AS status,
ISNULL(xstatuscrn, '') AS xstatuscrn,
ISNULL(((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = opcrnheader.zid AND xnum = opcrnheader.xstatuscrn)), '') AS statuscrn,
ISNULL(xvoucher, '') AS xvoucher,
ISNULL(xnote1, '') AS xnote1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = opcrnheader.zid AND xstaff = opcrnheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = opcrnheader.zid AND xstaff = opcrnheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = opcrnheader.zid AND xstaff = opcrnheader.xpreparer), '') AS preparer_xdeptname
FROM
opcrnheader
WHERE
LEFT(xcrnnum, 4) = 'SLR-'
AND xstatus NOT IN ('4', '', '6', '5', '3')
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
AND zid = '$zid'
ORDER BY xcrnnum DESC";


$params = [$xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
