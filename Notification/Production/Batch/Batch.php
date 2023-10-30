<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
xbatch,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xbomkey, '-') AS xbomkey,
ISNULL((SELECT xdesc FROM bmbomheader WHERE xbomkey = moheader.xbomkey AND zid = moheader.zid), '') AS xbomdesc,
ISNULL(xitem, '-') AS xitem,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = moheader.xitem AND zid = moheader.zid), '') AS xitemdesc,
ISNULL((SELECT xlong + ' (' + xcode + ')' FROM branchview WHERE xtype='Branch' AND  xcode = moheader.xwhcomp AND zid = moheader.zid), '') AS xwhcompdesc,
ISNULL(xqtyprd, 0) AS xqtyprd,
ISNULL((SELECT xlong + ' (' + xcode + ')' FROM branchview WHERE xtype='Branch' AND  xcode = moheader.xwh AND zid = moheader.zid), '') AS xwhdesc,		
ISNULL((SELECT (SELECT xlong FROM xcodes WHERE zid = moheader.zid AND xcode = moheader.xmoprcs AND xtype = 'Machine Name' AND zactive = '1') + ' (' + moheader.xmoprcs + ')'), '') AS xmoprcsdesc,
(ISNULL((SELECT (SELECT xlong FROM xcodes WHERE zid = moheader.zid AND xcode = moheader.xmachineno AND xtype = 'Machine No' AND zactive = '1') + ' (' + moheader.xmachineno + ')'), '')) AS xmachinenodesc,
ISNULL(xarmno, '-') AS xarmno,
ISNULL(xrefstaff, '-') AS xrefstaff,
ISNULL((SELECT xname FROM pdmst WHERE xposition = moheader.xrefstaff AND zid = moheader.zid), '') AS xrefstaffdesc,
(ISNULL((SELECT (SELECT xlong FROM xcodes WHERE zid = moheader.zid AND xcode = moheader.xshift AND xtype = 'Production Shift' AND zactive = '1') + ' (' + moheader.xshift + ')'), '')) AS xshiftdesc,
ISNULL(xnoworker, '-')  AS xnoworker,
ISNULL(xshiftengr, '-') AS xshiftengr,
ISNULL((SELECT xname FROM pdmst WHERE xposition = moheader.xshiftengr AND zid = moheader.zid), '') AS xshiftengrdesc,
ISNULL(xwastqty, 0 ) AS xwastqty,
(ISNULL((SELECT xlong FROM branchview WHERE zid = moheader.zid AND xtype = 'Branch' AND xcode = xwhwast) + ' (' + xwhwast + ')', '')) AS xwhwastdesc,
ISNULL(xwastitem, '-') AS xwastitem,
ISNULL((SELECT xdesc FROM caitem WHERE xitem = moheader.xwastitem AND zid = moheader.zid), '') AS xwastitemdesc,
ISNULL(xstatusmor, '-') AS xstatusmor,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = moheader.xstatusmor AND zid = moheader.zid), '-') AS descxstatusmor,
ISNULL(xlong, '-') AS xlong,
ISNULL(xstatus, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = moheader.xstatus AND zid = moheader.zid), '-') AS descxstatus,
ISNULL(xnote, '-') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory1), '') AS Approver_name1,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory1), '') AS Approver_designation1,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory1), '') AS Approver_xdeptname1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory2), '') AS Approver_name2,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory2), '') AS Approver_designation2,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory2), '') AS Approver_xdeptname2,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory3), '') AS Approver_name3,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory3), '') AS Approver_designation3,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory3), '') AS Approver_xdeptname3,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory4), '') AS Approver_name4,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory4), '') AS Approver_designation4,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory4), '') AS Approver_xdeptname4,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory5), '') AS Approver_name5,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory5), '') AS Approver_designation5,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignatory5), '') AS Approver_xdeptname5,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignreject), '') AS reject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignreject), '') AS reject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = moheader.zid AND xstaff = moheader.xsignreject), '') AS reject_xdeptname
FROM moheader
WHERE 
left(xbatch,4)='BAT-'
AND xstatus not in ('4','','6','7')
AND zid = ?
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?)
ORDER BY xbatch DESC;";


$params = [ $zid, $xposition, $xposition, $xposition ];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
