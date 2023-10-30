<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
zid,
ISNULL(xcase, '') AS xcase,
ISNULL(xrow, 0) AS xrow,
ISNULL(xtype, '') AS xtype,
ISNULL(xstatus, '') AS xstatus,
ISNULL(xsignatory1, '') AS xsignatory1,
ISNULL(REPLACE(CONVERT(varchar, xsigndate1, 3), '0000-00-00', '')  , '') AS xsigndate1,
ISNULL(xnote, '') AS xnote,
ISNULL(xptype, '') AS xptype,
ISNULL(xapprover, '') AS xapprover,
ISNULL((SELECT xname FROM pdmst WHERE xposition = pdsettlementdetails.xapprover AND zid = pdsettlementdetails.zid), '-') AS Appxname,
ISNULL((SELECT designationname FROM pdmstview WHERE xposition = pdsettlementdetails.xapprover AND zid = pdsettlementdetails.zid), '-') AS Appdesignationname,
ISNULL((SELECT departmentname FROM pdmstview WHERE xposition = pdsettlementdetails.xapprover AND zid = pdsettlementdetails.zid), '-') AS Appdepartmentname,
ISNULL((SELECT xstaff FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xstaff,
ISNULL((SELECT xname FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xname,
ISNULL((SELECT designationname FROM pdmstview WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xdesignation,
ISNULL((SELECT departmentname FROM pdmstview WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xdeptname,
ISNULL((SELECT xempcategory FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xempcategory,
ISNULL((SELECT xemptype FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '-') AS xemptype,
ISNULL((SELECT xgross FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), 0) AS xgross,
ISNULL((SELECT REPLACE(CONVERT(varchar, xdatejoin, 3), '0000-00-00', '')  FROM pdmst WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '') AS xdatejoin,
ISNULL((SELECT REPLACE(CONVERT(varchar, xenddate, 3), '0000-00-00', '')  FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xstaff = (SELECT xstaff FROM pdsettlement WHERE xcase = pdsettlementdetails.xcase AND zid = pdsettlementdetails.zid)), '') AS xenddate,
ISNULL((SELECT xreason FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase), '') AS xreason,
ISNULL((SELECT xadvamtexp FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase), 0) AS xadvamtexp,
ISNULL((SELECT xadvamtsal FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase), 0) AS xadvamtsal,
ISNULL((SELECT xothers FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase), 0) AS xothers,
ISNULL((SELECT xdeduction FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase), 0) AS xdeduction,
ISNULL((SELECT xlong FROM zstatus WHERE zid = pdsettlementdetails.zid AND xnum = (SELECT xstatus FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase)), '') AS xstatus,
ISNULL((SELECT xname FROM pdmst WHERE xstaff = (SELECT xpreparer FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase) AND zid = pdsettlementdetails.zid), '-') AS preparer_name,
ISNULL((SELECT designationname FROM pdmstview WHERE xstaff = (SELECT xpreparer FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase) AND zid = pdsettlementdetails.zid), '-') AS preparer_designationname,
ISNULL((SELECT departmentname FROM pdmstview WHERE xstaff = (SELECT xpreparer FROM pdsettlement WHERE zid = pdsettlementdetails.zid AND xcase = pdsettlementdetails.xcase) AND zid = pdsettlementdetails.zid), '-') AS preparer_departmentname
FROM 
pdsettlementdetails
where (xapprover = ?) and zid =?";


$params = [ $xposition,  $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
