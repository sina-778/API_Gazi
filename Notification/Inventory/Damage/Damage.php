<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
xtornum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL((SELECT xlong FROM branchview WHERE zid = imtorheader.zid AND xcode = imtorheader.xtwh), '') AS twhdesc,
ISNULL(xfwh, '-') AS xfwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xfwh), '-') AS xfbrname,
ISNULL(xref, '-') AS xref,
ISNULL(xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xtwh), '-') AS xtbrname,
ISNULL((SELECT xlong FROM departmentview WHERE zid = imtorheader.zid AND xcode = imtorheader.xregi), '-') AS regidesc,
ISNULL(xlong, '-') AS xlong,
ISNULL(xnote, '-') AS xnote,
ISNULL(xstatustor, '-') AS xstatustor,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imtorheader.xstatustor AND zid = imtorheader.zid), '-') AS statustordesc ,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer1_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer1_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS reviewer2_xdeptname
FROM imtorheader
WHERE 
LEFT(xtornum, 4) IN ('DAM-')
AND xstatustor NOT IN ('4', '11', '18', '6', '7', '1')
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?) 
AND zid = ?
ORDER BY xtornum DESC";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
