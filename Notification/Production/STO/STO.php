<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
xtornum,
ISNULL(xporeqnum, '') AS xporeqnum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(REPLACE(CONVERT(varchar, xdatereq, 3), '0000-00-00', ''), '-') AS xdatereq,
ISNULL(xfwh, '-') AS xfwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xfwh), '-') AS xfbrname,
ISNULL(xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xtwh), '-') AS xtbrname,
ISNULL(xpriority, '') AS xpriority,
ISNULL((SELECT xlong FROM departmentview WHERE zid = imtorheader.zid AND xcode = imtorheader.xregi), '-') AS regidesc,
ISNULL((SELECT xlong FROM xcodes WHERE zid = imtorheader.zid AND xcode = imtorheader.xprodnature), '-') AS xprodnaturedesc,
ISNULL(xlong, '-') AS xlong,
ISNULL((SELECT xlong FROM branchview WHERE zid = imtorheader.zid AND xcode = imtorheader.xtwh), '') AS twhdesc,
ISNULL(xstatustor, '-') AS xstatustor,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imtorheader.xstatustor AND zid = imtorheader.zid), '-') AS xstatusrdesc,
ISNULL(xnote, '-') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdeptname
FROM imtorheader
WHERE 
left(xtornum,4)='STO-'  and xstatustor not in ('4','11','18','6','7','')
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
AND zid = '$zid'
ORDER BY xtornum DESC;";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
