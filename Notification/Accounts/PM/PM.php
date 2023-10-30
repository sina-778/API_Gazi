<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT
xvoucher,  
ISNULL(xprime, 0) AS xprime,
ISNULL((SELECT xorg FROM cacus WHERE cacus.zid = arhed.zid AND cacus.xcus = arhed.xcus), '') AS xcusdesc,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xarnature, '') AS xarnature,
ISNULL(xref, '') AS xref,
ISNULL(xcus, '') AS xcus,
ISNULL((SELECT xorg FROM cacus WHERE cacus.zid = arhed.zid AND cacus.xcus = arhed.xcus), '') AS xorg,
ISNULL(REPLACE(CONVERT(varchar, xdateref, 3), '0000-00-00', ''), '-') AS xdateref,
ISNULL(xbank, '') AS xbank,
ISNULL((SELECT xname FROM cabank WHERE zid = arhed.zid AND xbank = arhed.xbank), '') AS xbankname,
ISNULL(xcur, '') AS xcur,
ISNULL(xacccr, '') AS xaccdr,
ISNULL((SELECT xdesc FROM acmst WHERE zid = arhed.zid AND xacc = arhed.xacccr), '') AS xaccdrdesc,
ISNULL(xnote, '') AS xnote,
ISNULL(xtypeobj, '') AS xtypeobj,
ISNULL(xwh, '') AS xwh,
ISNULL(xexch, 0) AS xexch,
ISNULL(xbase, 0) AS xbase,
ISNULL(xpaymenttype, '') AS xpaymenttype,
ISNULL(xsub, '') AS xsub,
ISNULL((SELECT xorg FROM acsubview WHERE zid = arhed.zid AND xacc = arhed.xaccdr and xsub = arhed.xsub), '') AS xsubdesc,
ISNULL((SELECT xlong FROM projectallview WHERE zid = arhed.zid AND xcode = arhed.xwh), '') AS xwhdesc,
ISNULL(xvatamt, 0) AS xvatamt,
ISNULL(xaitamt, 0) AS xaitamt,
ISNULL(xdocnum, '') AS xdocnum,
ISNULL(xpornum, '') AS xpornum,
ISNULL(xstatusmr, '') AS xstatusmr,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = arhed.xstatusmr AND zid = arhed.zid), '-') AS xstatusmrdesc,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = arhed.xstatusjv AND zid = arhed.zid), '-') AS xstatusjvdesc,
ISNULL((SELECT xprime FROM cacusbalview WHERE xcus = arhed.xcus AND zid = arhed.zid) , 0) AS  xcusledbal,
ISNULL((SELECT xtypetrn FROM cacusbalview WHERE xcus =arhed.xcus AND zid = arhed.zid),'') AS xtypetrn,
ISNULL((SELECT sum(xprime*xsign) from arhed where xcus =arhed.xcus AND xpornum = arhed.xpornum AND zid = arhed.zid) , 0) AS  xpobalance,
ISNULL(xnote1, '') AS xnote,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = arhed.zid AND xstaff = arhed.xpreparer), '') AS preparer_xdeptname
FROM arhed
WHERE xprime > 0 AND left(xvoucher,2)='PM' and xstatusmr='2'
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
