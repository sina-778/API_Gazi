<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xadjnum, '-') AS xadjnum,
ISNULL(xvoucher, '-') AS xvoucher,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL(xtrn, '-') AS xtrn,
ISNULL(xloannum, '-') AS xloannum,
ISNULL(xtypeobj, '-') AS xtypeobj,
ISNULL(xprime, 0) AS xprime,
ISNULL(xbank, '-') AS xbank,
ISNULL((SELECT xname FROM cabank WHERE zid = acloanadj.zid AND xbank = acloanadj.xbank), '-') AS xbankdesc,
ISNULL(xacccr, '-') AS xacc,
ISNULL((SELECT xdesc FROM acmst WHERE zid = acloanadj.zid AND xacc = acloanadj.xacc), '-') AS xaccdesc,
ISNULL(xsub, '-') AS xsub,
ISNULL((SELECT xorg FROM acsubview WHERE zid = acloanadj.zid AND xacc = acloanadj.xacc AND xsub = acloanadj.xsub), '-') AS xsubdesc,
ISNULL(xstatus, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acloanadj.xstatus AND zid = acloanadj.zid), '-') AS xstatusrdesc,
ISNULL(xstatusjv, '') AS xstatusjv,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = acloanadj.xstatusjv AND zid = acloanadj.zid), '-') AS xstatusjvdesc,
ISNULL(xsign, '-') AS  xsign,
ISNULL(xnote, '') AS xnote,
ISNULL(xnote1, '') AS xnote1,
ISNULL((SELECT xname FROM pdmstsignatoryview WHERE zid = acloanadj.zid AND xstaff = acloanadj.xpreparer), '') AS preparer_name,
ISNULL((SELECT xdesignation FROM pdmstsignatoryview WHERE zid = acloanadj.zid AND xstaff = acloanadj.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT xdeptname FROM pdmstsignatoryview WHERE zid = acloanadj.zid AND xstaff = acloanadj.xpreparer), '') AS preparer_xdeptname
FROM acloanadj
WHERE LEFT(xadjnum, 4) = 'LADJ' AND xstatus NOT IN ('1', '4')
AND (xsuperiorgl=? or xsuperior2=? or xsuperior3=?) 
AND zid = ?
order by xadjnum desc";


$params = [ $xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
