<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT zid,
xbillno,
ISNULL(xprime, 0) AS xprime,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acbill.xstatus AND zid = acbill.zid), '-') AS xstatusdesc,
ISNULL(xwh, '-') AS xwh,
ISNULL((SELECT xlong FROM branchview WHERE zid = acbill.zid AND xcode = acbill.xwh), '-') AS xwhdesc,
ISNULL(xamount, 0) AS xamount,
ISNULL(xacc, '-') AS xacc,
ISNULL((SELECT xdesc FROM acmst WHERE zid = acbill.zid AND xacc = acbill.xacc), '-') AS xaccdesc,
ISNULL(xlong, '') AS xlong,
ISNULL(xnote, '') AS xnote,
ISNULL(xnote1, '') AS xnote1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xposition = acbill.xsuperiorgl), '') AS Approver_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsuperiorgl), '') AS Approver_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsuperiorgl), '') AS Approver_xdeptname
FROM acbill
WHERE  left(xbillno,4)='PCR-'  and xstatus not in ('4','6','')
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
