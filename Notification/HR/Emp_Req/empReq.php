<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$zemail = $data['zemail'];

$sql =  "SELECT
ISNULL(xpdreqnum, '') AS xpdreqnum,
ISNULL(xwh, '') AS xwh,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xdeptname, '') AS xdeptname,
ISNULL(xdeadcountb, '') AS xdeadcountb,
ISNULL(xpositiondesc, '') AS xpositiondesc,
ISNULL(xdeadcounta, '') AS xdeadcounta,
ISNULL(xpositiontype, '') AS xpositiontype,
ISNULL(xsalbudget, '') AS xsalbudget,
ISNULL(xstaff, '') AS xstaff,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = pdreqheader.xstaff AND zid = pdreqheader.zid AND zactive = '1'), '-') AS staffn,
ISNULL(xlocation, '') AS xlocation,
ISNULL(xemptype, '') AS xemptype,
ISNULL(xadvertise, '') AS xadvertise,
ISNULL(xstatusreq, '') AS xstatusreq,
ISNULL(((SELECT xlong FROM zstatus WHERE zactive = 1 AND zid = pdreqheader.zid AND xnum = pdreqheader.xstatusreq)), '') AS xstatusreqdesc,
ISNULL(xrequirement, '') AS xrequirement,
ISNULL(REPLACE(CONVERT(varchar, xpropdate, 3), '0000-00-00', ''), '-') AS xpropdate,
ISNULL(xmotive, '') AS xmotive,
ISNULL(xsalprop, '') AS xsalprop,
ISNULL(xtransfer, '') AS xtransfer,
ISNULL(xbenefit, '') AS xbenefit,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = pdreqheader.zid AND xstaff = pdreqheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = pdreqheader.zid AND xstaff = pdreqheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = pdreqheader.zid AND xstaff = pdreqheader.xpreparer), '') AS preparer_xdeptname
FROM
pdreqheader
WHERE
LEFT(xpdreqnum, 4) = 'ERQ-' AND xstatusreq <> '' and 
xsuperiorsp=? and zid = ? 
--and xwh ='';
order by xpdreqnum desc";


$params = [ $zemail,  $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
