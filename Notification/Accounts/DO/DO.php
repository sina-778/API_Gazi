<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT ISNULL(xlong, '') AS xlong,
ISNULL(xgrninvno, '') AS xgrninvno,
ISNULL(xpornum, '') AS xpornum,
ISNULL(xvoucher, '') AS xvoucher,
ISNULL(xinvnum, '') AS xinvnum,
ISNULL(xsup, '') AS  xsup,
ISNULL((SELECT xorg FROM cacus WHERE cacus.zid = apsupinvm.zid AND cacus.xcus = apsupinvm.xsup), '') AS xsupname,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM xcodes WHERE xcode = apsupinvm.xwh AND xtype = 'Branch' AND zid = apsupinvm.zid), '') AS xwhdec,
ISNULL(xinvamt, 0) AS xinvamt,
ISNULL(xgrnamt, 0) AS xgrnamt,
ISNULL(xadvamt, 0) AS xadvamt,
ISNULL(xapvamt, 0) AS xapvamt,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = apsupinvm.xstatus AND zid = apsupinvm.zid), '-') AS xstatusrdesc,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = apsupinvm.xstatusjv AND zid = apsupinvm.zid), '-') AS xxstatusjvdesc,
ISNULL(xlong, '') AS xlong,
ISNULL(xnote, '') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xpreparer), '') AS preparer_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory1), '') AS reviewer1_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory1), '') AS reviewer1_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory1), '') AS reviewer1_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory2), '') AS reviewer2_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory2), '') AS reviewer2_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignatory2), '') AS reviewer2_xdeptname,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignreject), '') AS signreject_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignreject), '') AS signreject_designation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = apsupinvm.zid AND xstaff = apsupinvm.xsignreject), '') AS signreject_xdeptname
FROM apsupinvm
WHERE 
xstatus not in ('1','4','7')
AND zid = '$zid'
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition') order by xgrninvno desc";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
