<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xtornum, '-') AS xtornum,
ISNULL(REPLACE(CONVERT(VARCHAR, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL((SELECT xlong FROM branchview WHERE zid = imtorheader.zid AND xcode = imtorheader.xtwh), '-') AS twhdesc,
ISNULL(xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM departmentview WHERE zid = imtorheader.zid AND xcode = imtorheader.xregi), '-') AS regidesc,
ISNULL(xshift, '-') AS xshift,
ISNULL(xlong, '-') AS xlong,
ISNULL(xstatustor, '-') AS xstatustor,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imtorheader.xstatustor AND zid = imtorheader.zid), '-') AS statustordesc ,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdeptname
FROM imtorheader 
WHERE 
zid = $zid 
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') 
AND LEFT(xtornum, 2) = 'RR' 
AND xstatustor NOT IN ('4','11','18','6','7','')
ORDER BY xtornum DESC;";


$params = [ $zid, $xposition, $xposition, $xposition];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
