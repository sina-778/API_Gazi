<?php

require_once '../../index.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$zid = $data['zid'];
$xbomkey=$data["xbomkey"];

$sql =  "SELECT 
xbomrow,
xbomcomp,
ISNULL((SELECT xdesc FROM caitem WHERE zid = bmbomdetail.zid AND xitem = bmbomdetail.xbomcomp), '') AS xdesc,
ISNULL(xwh, '') AS xwh,
ISNULL(xqtymix, 0) AS xqtymix,
ISNULL(xunit, '') AS xunit,
ISNULL(xstype, '') AS xstype
FROM bmbomdetail
WHERE xbomkey = ? AND zid = ?
ORDER BY xbomrow;";


$params = [ $xbomkey, $zid];

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($rows);
} catch (PDOException $e) {
    echo '{"error": "' . $e->getMessage() . '"}';
}
