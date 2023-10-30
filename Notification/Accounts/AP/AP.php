<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  " SELECT 
xvoucher,
ISNULL(xprime, 0) AS xprime,
ISNULL((SELECT xorg FROM cacus WHERE cacus.zid = arhed.zid AND cacus.xcus = arhed.xcus), '') AS xcusdesc,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xarnature, '') AS xarnature,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE cacus.zid = arhed.zid AND cacus.xcus = arhed.xcus), '') AS xorg,
ISNULL(xpaymenttype, '') AS xpaymenttype,
ISNULL(xbank, '') AS xbank,
ISNULL((SELECT xdesc FROM acmst WHERE zid = arhed.zid AND xacc = arhed.xbank), '') AS xbankname, 
ISNULL(xtypeobj, '') AS xtypeobj,
ISNULL(xwh, '') AS xwh,
ISNULL((SELECT xlong FROM projectallview WHERE zid = arhed.zid AND xcode = arhed.xwh), '') AS xwhdesc,
ISNULL(xbalprime, 0) AS xbalprime,
ISNULL(xvatamt, 0) AS xvatamt,
ISNULL(xref, '') AS xref,
ISNULL(REPLACE(CONVERT(varchar, xdateref, 3), '0000-00-00', ''), '-') AS xdateref,
ISNULL(xaitamt, 0) AS xaitamt,
ISNULL(xstatusmr, '') AS xstatusmr,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = arhed.xstatusmr AND zid = arhed.zid), '-') AS xstatusmrdesc,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = arhed.xstatusjv AND zid = arhed.zid), '-') AS xstatusjvdesc,
ISNULL(xdocnum, '') AS xdocnum,
ISNULL(xnote, '') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_xdeptname
FROM arhed
WHERE   left(xvoucher,4)='ADAP'  and xstatusmr not in ('4','11','18','6','')
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?) 
AND zid = ?
order by xvoucher desc";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
