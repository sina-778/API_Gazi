<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql = "SELECT
ISNULL(xtornum, '') AS xtornum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xtitem, '') AS xtitem,
ISNULL(xtypeobj, '') AS xtypeobj,
ISNULL((SELECT xlong FROM branchview WHERE zid = imtorheader.zid AND xcode = imtorheader.xfwh), '') AS twhdesc,
ISNULL((SELECT xname FROM pdmst WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer,
ISNULL(xstatustor, '') AS xstatustor,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = imtorheader.zid AND xnum = imtorheader.xstatustor), '') AS statusName,
ISNULL(xfwh, '') AS xfwh,
ISNULL(REPLACE(CONVERT(varchar, xdatereq, 3), '0000-00-00', ''), '-') AS xdatereq,
ISNULL(xref, '') AS xref,
ISNULL(xlong, '') AS xlong,
ISNULL(xreqtype, '') AS xreqtype,
ISNULL(xpriority, '') AS  xpriority,
ISNULL((SELECT xlong FROM xpurchasenview WHERE zid = imtorheader.zid AND xcode = imtorheader.xprodnature), '') AS xprodnaturedesc,
ISNULL((SELECT xname FROM pdmst WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer1_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer1_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer1_xdeptname
FROM
imtorheader
WHERE
LEFT(xtornum, 3) = 'SPR'
AND xstatustor NOT IN ('4','11','18','6','7','')
AND zid = ?
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?)
ORDER BY
xtornum DESC";

//(select xlong from zstatus where xnum = poreqheader.xstatusreq and zid = poreqheader.zid)

$params = [$zid, $xposition, $xposition, $xposition];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
