<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
ISNULL(xporeqnum, '') AS xporeqnum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xstatus, '') AS xstatus,
ISNULL(((select xlong from zstatus where zactive = 1 and  zid= poreqheader.zid and xnum =poreqheader.xstatus ) ), '') AS statusName,
ISNULL(xstatusreq, '') AS xstatusreq,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND xnum = poreqheader.xstatusreq AND zid = poreqheader.zid), '') AS xstatusreqDesc,
ISNULL(xtypeobj, '') AS xtypeobj,
ISNULL(xtype, '') AS xtype,
ISNULL(xtwh, '') AS xtwh,
ISNULL((SELECT xlong FROM branchview WHERE zid = poreqheader.zid AND xcode = poreqheader.xtwh), '') AS storeName,
ISNULL(xnote, '') AS xnote,
ISNULL(xnote1, '') AS xnote1,
ISNULL((select sum(xlineamt) FROM poreqdetail WHERE xporeqnum = poreqheader.xporeqnum and zid = poreqheader.zid), 0) AS total_amt,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory1), '') AS reviewer1_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory1), '') AS reviewer1_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory1), '') AS reviewer2_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory2), '') AS reviewer2_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory2), '') AS reviewer2_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignatory2), '') AS reviewer2_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignreject), '') AS signreject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignreject), '') AS signreject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xsignreject), '') AS signreject_xdeptname
FROM poreqheader
WHERE 
LEFT(xporeqnum, 4) IN ('PR--', 'JN--', 'DOC-')
AND xtype = 'Cash'
AND xstatusreq NOT IN ('4', '7')
AND zid = $zid
AND (xsuperiorsp = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
ORDER BY xporeqnum DESC";

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
