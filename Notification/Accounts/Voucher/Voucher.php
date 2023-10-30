<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  " SELECT ISNULL(xlong, '') AS xlong,
ISNULL(xvoucher, '') AS xvoucher,
ISNULL(xtrn, '') AS xtrn,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xref, '') AS xref,
ISNULL(xlcno, '') AS xlcno,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM xcodes WHERE xcode = acheader.xwh AND xtype = 'Branch' AND zid = acheader.zid), '') AS xwhdec,
ISNULL(xchequeno, '') AS xchequeno,
ISNULL(REPLACE(CONVERT(varchar, xdatechq, 3), '0000-00-00', ''), '-') AS xdatechq,
ISNULL(xbank, '') AS xbank,
ISNULL((SELECT xname FROM cabank WHERE xbank = acheader.xbank AND zid = acheader.zid), '') AS bname,
ISNULL(xyear, '') AS xyear,
ISNULL(xper, '') AS xper,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acheader.xstatus AND zid = acheader.zid), '-') AS xstatusrdesc,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acheader.xstatusjv AND zid = acheader.zid), '-') AS xxstatusjvdesc,
ISNULL(xlong, '') AS xlong,
ISNULL(xnote, '') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE xstaff = acheader.xpreparer AND zid = acheader.zid), '') AS preparer,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE xstaff = acheader.xpreparer AND zid = acheader.zid), '') AS designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE xstaff = acheader.xpreparer AND zid = acheader.zid), '') AS deptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE xstaff = acheader.xsignatory1 AND zid = acheader.zid), '') AS signname,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE xstaff = acheader.xsignatory1 AND zid = acheader.zid), '') AS signdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE xstaff = acheader.xsignatory1 AND zid = acheader.zid), '') AS signdeptname
FROM acheader
WHERE xstatusjv = '9'
AND xstatus NOT IN ('4', '1', '7')
AND zid = '$zid'
AND (xsuperiorgl = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') order by xvoucher desc";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
