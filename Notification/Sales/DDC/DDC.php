<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(a.xdocnum, '-') AS xdocnum,
ISNULL(REPLACE(CONVERT(VARCHAR, a.xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(a.xfwh, '-') AS xfwh,
ISNULL((SELECT xlong FROM xcodes WHERE zid = a.zid AND xcode = a.xfwh and xtype='Branch'), '-') AS xfwhdesc,
ISNULL(a.xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM xcodes WHERE zid = a.zid AND xcode = a.xtwh and xtype='Branch' and zactive=1), '-') AS xtwhdesc,
ISNULL(a.xtornum, '-') AS xtornum,
ISNULL(xvehicle, '-') AS xvehicle, 
ISNULL(xdrivername, '-') AS  xdrivername,
ISNULL(xphone, '-') AS xphone,
ISNULL(a.xstatus1, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = a.xstatus1 AND zid = a.zid), '-') AS statustordesc ,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = a.zid AND xstaff = a.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = a.zid AND xstaff = a.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = a.zid AND xstaff = a.xpreparer), '') AS preparer_xdeptname
FROM imdcheader a
join imdcshortview b 
on a.zid = b.zid and a.xdocnum = b.xdocnum 
WHERE 
a.zid = $zid  
AND (a.xidsup = '$xposition' OR a.xsuperior2 = '$xposition' OR a.xsuperior3 = '$xposition') 
AND left(a.xdocnum,4)='DDC-'
AND  xstatusdoc='19' 
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
