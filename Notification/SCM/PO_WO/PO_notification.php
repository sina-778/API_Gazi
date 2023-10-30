<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "
SELECT 
    ISNULL(xpornum, '') AS xpornum,
    ISNULL(REPLACE(CONVERT(varchar, xdate, 3), '0000-00-00', ''), '-') AS xdate,
    ISNULL(xempunit, '') AS xempunit,
    ISNULL(xstatus, '') AS xstatus,
    ISNULL((SELECT xlong FROM zstatus WHERE zactive = 1 AND xnum = poordheader.xstatus AND zid = poordheader.zid), '') AS statusName,
    ISNULL((SELECT xorg FROM cacus WHERE zid = poordheader.zid AND xcus = poordheader.xcus), '') AS cusdesc,
    ISNULL(xcus, '') AS xcus,
    ISNULL(xtype, '') AS xtype,
    ISNULL((SELECT xpoval FROM poordheaderview WHERE zid = poordheader.zid AND xpornum = poordheader.xpornum), 0) AS povalue,
    ISNULL(xporeqnum, '') AS xporeqnum,
    ISNULL(xstatuspor, '') AS xstatuspor,
    ISNULL((SELECT xtotqty FROM poordheaderview WHERE zid = poordheader.zid AND xpornum = poordheader.xpornum), 0) AS xtotqty,
    ISNULL((SELECT xtotval FROM poordheaderview WHERE zid = poordheader.zid AND xpornum = poordheader.xpornum), 0) AS xtotval,
    ISNULL(xtypeobj, '') AS xtypeobj,
    ISNULL(xrem, '') AS xrem,
    ISNULL(xcur, '') AS xcur,
    ISNULL(xvatamt, 0) AS xvatamt,
    ISNULL(xvatrate, 0) AS xvatrate,
    ISNULL(xwh, '') AS xwh,
	ISNULL((SELECT xlong FROM projectallview WHERE zid = poordheader.zid AND xcode = poordheader.xwh), '') AS xwhdesc,
    ISNULL((SELECT xname FROM pdmst WHERE xstaff = poordheader.xpreparer AND zid = poordheader.zid), '') AS prepname,
    ISNULL((SELECT xdesignation FROM pdmst WHERE xstaff = poordheader.xpreparer AND zid = poordheader.zid), '') AS xdesignation,
    ISNULL((SELECT xdeptname FROM pdmst WHERE xstaff = poordheader.xpreparer AND zid = poordheader.zid), '') AS xdeptname,
    ISNULL((SELECT xlong FROM projectallview WHERE xcode = poordheader.xproject AND zid = poordheader.zid), '') AS pname,
    ISNULL((SELECT xname FROM pdmst WHERE zid = poordheader.zid AND xstaff = poordheader.xsignatory1), '') AS reviewer1_name,
    ISNULL((SELECT xdesignation FROM pdmst WHERE zid = poordheader.zid AND xstaff = poordheader.xsignatory1), '') AS reviewer1_designation,
    ISNULL((SELECT xdeptname FROM pdmst WHERE zid = poordheader.zid AND xstaff = poordheader.xsignatory1), '') AS reviewer1_xdeptname
FROM poordheader
WHERE LEFT(xpornum, 4) IN ('PO--', 'WO--') AND xstatus NOT IN ('4', '7')
 and (xidsup =? or xsuperior2= ? or xsuperior3= ? ) and zid=? order by xpornum desc";

//(select xlong from zstatus where xnum = poordheader.xstatuspor and zid = poordheader.zid) as 

$params = [$xposition, $xposition, $xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
