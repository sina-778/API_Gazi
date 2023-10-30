<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
xgrnnum,
ISNULL(REPLACE(CONVERT(VARCHAR, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xcus, '') AS xcus,
ISNULL((select xorg from cacus where zid = pogrnheader.zid and xcus = pogrnheader.xcus), '') AS sup,
ISNULL(xref, '') AS  xref,
ISNULL(xnote, '') AS xnote,
ISNULL(xstatusgrn, '') AS xstatusgrn,
ISNULL((select xlong from zstatus where xnum = pogrnheader.xstatusgrn and zid =pogrnheader.zid), '') AS xstatusgrn,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM branchview WHERE zid = pogrnheader.zid AND xcode = pogrnheader.xwh), '-') AS xwhdesc,
ISNULL(xpornum, '') AS xpornum,
ISNULL(xnote, '') AS xnote,
ISNULL(REPLACE(CONVERT(VARCHAR, xdatereceive, 3), '0000-00-00', ''), '-') AS xnote1,
ISNULL(xstatusdoc, '') AS xstatusdoc,
ISNULL((select xlong from zstatus where xnum = pogrnheader.xstatusdoc and zid =pogrnheader.zid), '') AS statusdocdesc,
ISNULL(xgateentryno, '') AS xgateentryno,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xpreparer), '') AS preparer,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xpreparer), '') AS designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xpreparer), '') AS deptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xsignreject), '') AS signreject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xsignreject), '') AS signreject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = pogrnheader.zid AND xstaff = pogrnheader.xsignreject), '') AS signreject_xdeptname
FROM pogrnheader
WHERE 
(xsuperiorsp = ? OR xsuperior2 = ? OR xsuperior3 = ?) 
AND zid = ? AND  xstatusgrn = '1' 
AND LEFT(xgrnnum, 3) = 'GRN' 
AND xstatusdoc NOT IN ('1', '4', '7') 
ORDER BY xgrnnum;";


$params = [$xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
