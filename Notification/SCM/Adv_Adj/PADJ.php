<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
ISNULL(xporeqnum, '') AS xporeqnum,
ISNULL(xadvnum, '') AS xadvnum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xstatus, '') AS xstatus,
ISNULL(((select xlong from zstatus where zactive = 1 and  zid= poreqheader.zid and xnum =poreqheader.xstatus ) ), '') AS statusName,
ISNULL(xstatusreq, '') AS xstatusreq,
ISNULL(((select xlong from zstatus where zactive = 1 and  zid= poreqheader.zid and xnum =poreqheader.xstatusreq ) ), '') AS statusreqdesc,
ISNULL(xtypeobj, '') AS xtypeobj,
ISNULL(xtype, '') AS xtype,
ISNULL(xfwh, '') AS xfwh,
ISNULL((SELECT xlong FROM branchview WHERE zid = poreqheader.zid AND xcode = poreqheader.xfwh), '') AS storeName,
ISNULL(xnote, '') AS xnote,
ISNULL(xstaff, '') AS xstaff,
ISNULL((SELECT xtornum FROM poreqheader WHERE zid = poreqheader.zid AND xporeqnum = poreqheader.xadvnum), '') AS tornum,
ISNULL(xprime, 0) AS xprime,
ISNULL(xnote1, '') AS xnote1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = poreqheader.zid AND xstaff = poreqheader.xstaff), '') AS sname,
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
xtype='Cash' and left(xporeqnum,4) in ('PADJ') and xstatusreq  not in ('4','7','','0')
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
