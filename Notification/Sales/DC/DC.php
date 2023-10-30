<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xdornum, '') AS xdornum,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE xcus = opdoheader.xcus AND zid = opdoheader.zid), '-') AS xorg,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM xcodes WHERE xtype = 'Branch' AND zid = opdoheader.zid AND xcode = opdoheader.xwh), '-') AS xwhdesc,
ISNULL(xpaymenttype, '') AS xpaymenttype,
ISNULL(xopincapply, '') AS xopincapply,
ISNULL(xsonumber, '') AS xsonumber,
ISNULL(xref, '') AS xref,
ISNULL(xtso, '') AS xtso,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opdoheader.xtso AND zid = opdoheader.zid AND zactive = '1'), '-') AS Executive,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opdoheader.xzm AND zid = opdoheader.zid AND zactive = '1'), '-') AS ZM,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = opdoheader.xdm AND zid = opdoheader.zid AND zactive = '1'), '-') AS DSM,
ISNULL(xterritory, '') AS Base,
ISNULL(xzone, '') AS Zone,
ISNULL(xdivision, '') AS Division,
ISNULL(xexpamt, 0) AS xexpamt,
ISNULL(xdivision, '') AS xdisctype,
ISNULL(xvoucher, '') AS xvoucher,
ISNULL(xnote1, '') AS xnote1,
ISNULL(xstatus, '') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = opdoheader.zid AND xnum = opdoheader.xstatus), '') AS status,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = opdoheader.zid AND xstaff = opdoheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = opdoheader.zid AND xstaff = opdoheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = opdoheader.zid AND xstaff = opdoheader.xpreparer), '') AS preparer_xdeptname
FROM 
opdoheader
WHERE 
LEFT(xdornum, 3) = 'DC-' AND xstatus NOT IN ('4', '1', '', '5')
AND (xidsup = '$xposition' OR xsuperior2 = '$xposition' OR xsuperior3 = '$xposition')
AND zid = '$zid'
ORDER BY xdornum DESC
";

//(select xlong from zstatus where xnum = poreqheader.xstatusreq and zid = poreqheader.zid)

$params = [ $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
