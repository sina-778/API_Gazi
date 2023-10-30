<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xbillno=$data["xbillno"];

$sql =  "select zid, xbillno, 
xrow,
ISNULL(xstaff, '') AS  xstaff, 
ISNULL((SELECT xname FROM pdmst WHERE zid = acbilldetail.zid AND xstaff = acbilldetail.xstaff), '') AS name	,
ISNULL(xprime, 0) AS xprime,
ISNULL(xpurpose, '') AS xpurpose,
ISNULL(xlong, '') AS xlong
from acbilldetail where xbillno = ? AND zid = ? 
ORDER BY xrow";


$params = [ $xbillno, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
