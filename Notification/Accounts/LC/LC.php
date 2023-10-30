<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xreqnum, '-') AS xreqnum,
ISNULL(xlcno, '-') AS xlcno,
ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
ISNULL((SELECT xlctype FROM lcinfo WHERE zid = lcreqheader.zid AND xbank = lcreqheader.xlcno), '-') AS xlctype,
ISNULL(xcus, '-') AS xcus,
ISNULL(xorg, '-') AS xorg,
ISNULL(xgrnnum, '') AS xgrnnum,
ISNULL(xtype, '-') AS xtype,
ISNULL((SELECT xcur FROM lcinfo WHERE zid = lcreqheader.zid AND xbank = lcreqheader.xlcno), '-') AS xcur,
ISNULL((SELECT xexch FROM lcinfo WHERE zid = lcreqheader.zid AND xbank = lcreqheader.xlcno), 0) AS xexch,
ISNULL(xstatus, '-') AS xstatus,
ISNULL((SELECT xlong FROM zstatus WHERE xnum = lcreqheader.xstatus AND zid = lcreqheader.zid), '-') AS statusdesc,
ISNULL(xnote1, '') AS xnote1,
ISNULL((SELECT xname FROM pdmst WHERE zid = lcreqheader.zid AND xstaff = lcreqheader.xpreparer), '') AS preparer_name,
ISNULL((SELECT designationname FROM pdmstview WHERE zid = lcreqheader.zid AND xstaff = lcreqheader.xpreparer), '') AS preparer_xdesignation,
ISNULL((SELECT departmentname FROM pdmstview WHERE zid = lcreqheader.zid AND xstaff = lcreqheader.xpreparer), '') AS preparer_xdeptname
FROM lcreqheader
WHERE xstatus not in ('4','1')
AND (xidsup = ? OR xsuperior2 = ? OR xsuperior3 = ?)
AND zid = ?
order by xreqnum desc
";

//(select xlong from zstatus where xnum = poreqheader.xstatusreq and zid = poreqheader.zid)

$params = [ $xposition, $xposition,$xposition,$zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
