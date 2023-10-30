<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
xbillno,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xwh, 0) AS xprime,
ISNULL(xstatus, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acbill.xstatus AND zid = acbill.zid), '-') AS xstatusrdesc,
ISNULL(xtrn, '-') AS xtrn,
ISNULL(xprime, 0) AS xprime,
ISNULL(xamount, 0) AS xamount,
ISNULL(xstaff, '-') AS xstaff,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xstaff), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xstaff), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xstaff), '') AS preparer_xdeptname,
ISNULL(xwh, '-') AS xwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = acbill.zid AND xcode = acbill.xwh), '-') AS xfbrname,
ISNULL(xaccdr, '-') AS xaccdr,
ISNULL((SELECT xdesc FROM acmst WHERE zid = acbill.zid AND xacc = acbill.xaccdr), '') AS xaccdrdesc,
ISNULL(xacccr, '-') AS xacccr,
ISNULL((SELECT xdesc FROM acmst WHERE zid = acbill.zid AND xacc = acbill.xacccr), '') AS xacccrdesc,
ISNULL((SELECT xlong FROM zstatus WHERE zid = acbill.zid AND xnum = acbill.xrecstatus), '-') AS recdesc,
ISNULL(xlong, '-') AS xlong,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory1), '') AS reviewer1_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory1), '') AS reviewer1_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory1), '') AS reviewer1_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory2), '') AS reviewer2_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory2), '') AS reviewer2_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignatory2), '') AS reviewer2_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignreject), '') AS signreject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignreject), '') AS signreject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acbill.zid AND xstaff = acbill.xsignreject), '') AS signreject_xdeptname
FROM acbill
WHERE 
left(xbillno, 4) = 'BL--'  
AND xstatus NOT IN ('4', '6', '') 
AND zid = ? and (xsuperiorgl= ? or xsuperior2= ? or xsuperior3=?)  
ORDER BY xbillno DESC;";


$params = [ $zid, $xposition, $xposition, $xposition ];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
