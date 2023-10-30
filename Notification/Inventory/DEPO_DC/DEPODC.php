<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xdocnum, '-') AS xdocnum,
ISNULL(REPLACE(CONVERT(VARCHAR, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xfwh, '-') AS xfwh,
ISNULL((SELECT xlong FROM xcodes WHERE zid = imdcheader.zid AND xcode = imdcheader.xfwh and xtype='Branch'), '-') AS xfwhdesc,
ISNULL(xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM xcodes WHERE zid = imdcheader.zid AND xcode = imdcheader.xtwh and xtype='Branch' and zactive=1), '-') AS xtwhdesc,
ISNULL(xtornum, '-') AS xtornum,
ISNULL(xvehicle, '-') AS xvehicle, 
ISNULL(xdrivername, '-') AS  xdrivername,
ISNULL(xphone, '-') AS xphone,
ISNULL(xstatus, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imdcheader.xstatus AND zid = imdcheader.zid), '-') AS status ,
ISNULL(xstatusdoc, '-') AS xstatusdoc,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imdcheader.xstatusdoc AND zid = imdcheader.zid), '-') AS statusdoc ,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imdcheader.zid AND xstaff = imdcheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imdcheader.zid AND xstaff = imdcheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imdcheader.zid AND xstaff = imdcheader.xpreparer), '') AS preparer_xdeptname
FROM imdcheader 
WHERE 
zid = $zid 
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') 
AND left(xdocnum,4)='DDC-'
AND xstatus not in ('4','','6','5','1')
ORDER BY xdocnum DESC";


$params = [ $zid, $xposition, $xposition, $xposition];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
