<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
    zid,
    xbomkey,
    ISNULL(xdesc, '-') AS xdesc,
    ISNULL(xitem, '-') AS xitem,
    ISNULL((SELECT xdesc FROM caitem WHERE xitem = bmbomheader.xitem AND zid = bmbomheader.zid), '') AS xitemdesc,
    ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
    ISNULL(xpreferbatchqty, 0) AS xpreferbatchqty,
    ISNULL(xstatus, '-') AS xstatustor,
    ISNULL((SELECT xlong FROM zstatus WHERE xnum = bmbomheader.xstatus AND zid = bmbomheader.zid), '-') AS descxstatustor,
    ISNULL(xlong, '-') AS xlong,
    ISNULL(xnote1, '-') AS xnote1,
    ISNULL((SELECT sum(xqtymix) FROM bmbomdetail WHERE xbomkey = bmbomheader.xbomkey AND zid = bmbomheader.zid), 0) AS totalQty,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xpreparer), '') AS preparer_name,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xpreparer), '') AS preparer_xdesignation,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xpreparer), '') AS preparer_xdeptname,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory1), '') AS Approver_name1,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory1), '') AS Approver_designation1,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory1), '') AS Approver_xdeptname1,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory2), '') AS Approver_name2,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory2), '') AS Approver_designation2,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory2), '') AS Approver_xdeptname2,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory3), '') AS Approver_name3,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory3), '') AS Approver_designation3,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory3), '') AS Approver_xdeptname3,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory4), '') AS Approver_name4,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory4), '') AS Approver_designation4,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory4), '') AS Approver_xdeptname4,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory5), '') AS Approver_name5,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory5), '') AS Approver_designation5,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignatory5), '') AS Approver_xdeptname5,
    ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignreject), '') AS reject_name,
    ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignreject), '') AS reject_designation,
    ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = bmbomheader.zid AND xstaff = bmbomheader.xsignreject), '') AS reject_xdeptname
    FROM bmbomheader
    WHERE 
    LEFT(xbomkey, 4) = 'BM--'
    AND xstatus NOT IN ('4', '', '6', '7')
    AND zid = ?
    AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?)
    ORDER BY xbomkey DESC;";


$params = [ $zid, $xposition, $xposition, $xposition ];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
