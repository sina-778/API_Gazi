<?php

header('Content-Type: application/json');

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'GET':
        getMethod();
        break;

    default:
        echo '{"name": "Data not found"}';
        break;
}

function getMethod() {
    include "index.php";
    
    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();
    $xsonum = isset($_GET['xsonum']) ? $_GET['xsonum'] : die();

    $sql = "SELECT 
                ISNULL(xrow, 0) AS xrow,
                ISNULL(xitem, '') AS xitem,
                ISNULL(xqtyreq, 0) AS xqtyreq,
                ISNULL((select xdesc from caitem where zid =opsodetail.zid and xitem = opsodetail.xitem ), '') AS itemName,
                ISNULL(xdisc, 0.0) AS xdisc,
                ISNULL(xdiscamt, 0.0) AS xdiscamt,
                ISNULL(xdiscad, 0.0) AS xdiscad,
                ISNULL(xdiscadamt, 0.0) AS xdiscadamt,
                ISNULL(xrate, 0.0) AS xrate,
                ISNULL(xlineamt, 0.0) AS xlineamt,
                ISNULL(xpartno, '') AS xpartno,
                ISNULL(xmasteritem, '') AS xmasteritem,
	            ISNULL((select xdesc from caitem where zid =opsodetail.zid and xitem = opsodetail.xmasteritem ), '') AS masterItemName
            FROM
                opsodetail
            WHERE
                zid = ? AND xsonumber = ?";
    $params = array($zid, $xsonum);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $rows = array();

    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }

    if (empty($rows)) {
        http_response_code(404);
    } else {
        echo json_encode($rows);
        http_response_code(200);
    }

    sqlsrv_free_stmt($stmt);
}
