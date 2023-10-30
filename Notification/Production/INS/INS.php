<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
xtornum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xfwh, '-') AS xfwh,
ISNULL((SELECT xlong FROM branchview WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xfwh), '-') AS xfwhdesc,
ISNULL(xqtyfin, 0) AS xqtyfin,
ISNULL(xparentitem, '') AS xparentitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = imtorheader.zid AND xitem = imtorheader.xparentitem), '-') AS xparentitemdesc,
ISNULL(xwh, '-') AS xwh,
ISNULL((SELECT xlong FROM branchview WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xwh), '-') AS xwhdesc,
ISNULL((SELECT xunit FROM caitem WHERE zid = imtorheader.zid AND xitem = imtorheader.xparentitem), '') AS xunit,
ISNULL(xwastitem, '') AS xwastitem,
ISNULL((SELECT xdesc FROM caitem WHERE zid = imtorheader.zid AND xitem = imtorheader.xwastitem), '-') AS xwastitemdesc,
ISNULL(xtwh, '-') AS xtwh,
ISNULL((SELECT xlong FROM branchview WHERE xtype = 'Branch' AND zid = imtorheader.zid AND xcode = imtorheader.xtwh), '-') AS xtwhdesc,
ISNULL(xwastqty, 0) AS xwastqty,
ISNULL(xstatustor, '-') AS xstatustor,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = imtorheader.xstatustor AND zid = imtorheader.zid), '-') AS xstatustordesc,
ISNULL(xlong, '-') AS xlong,
ISNULL(xnote, '-') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS Approver_name1,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS Approver_designation1,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory1), '') AS Approver_xdeptname1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory2), '') AS Approver_name2,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory2), '') AS Approver_designation2,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory2), '') AS Approver_xdeptname2,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory3), '') AS Approver_name3,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory3), '') AS Approver_designation3,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory3), '') AS Approver_xdeptname3,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory4), '') AS Approver_name4,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory4), '') AS Approver_designation4,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory4), '') AS Approver_xdeptname4,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory5), '') AS Approver_name5,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory5), '') AS Approver_designation5,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignatory5), '') AS Approver_xdeptname5,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignreject), '') AS reject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignreject), '') AS reject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = imtorheader.zid AND xstaff = imtorheader.xsignreject), '') AS reject_xdeptname
FROM imtorheader
WHERE 
left(xtornum,4)='FINS'  and xstatustor not in ('4','','6','7')				
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
