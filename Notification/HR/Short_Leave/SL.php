<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xposition = $data['xposition'];

$sql =  "SELECT 
ISNULL(xrow, 0) AS xrow,
ISNULL(xrow, 0) AS xyearperdate,
ISNULL(xstaff, '') AS xstaff,
ISNULL(xname, '') AS xname,
ISNULL((SELECT designationname FROM pdmstview WHERE zid = pdleavehourlyview.zid AND xstaff = pdleavehourlyview.xstaff), '') AS designation,
ISNULL(xdate, '') AS xdate,
ISNULL(xtypeleave, '') AS xtypeleave,
ISNULL(CONVERT(VARCHAR(8), xshiftimout, 108), '') AS xshiftimout,
ISNULL(CONVERT(VARCHAR(8), xshiftimin, 108), '') AS xshiftimin,
ISNULL(xstatus, '') AS xstatus,
ISNULL(xhour, 0) AS xhour
FROM 
pdleavehourlyview
WHERE 
(xsid = ? OR xsuperior2 = ? OR xsuperior3 = ?) AND
zid=?
AND xstatus NOT IN ('Open', 'Confirmed', 'Rejected')
AND xdate > '2019-11-01'
ORDER BY 
xrow";


$params = [ $xposition, $xposition,$xposition, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
