<?php

include "index.php";

header('content-type: application/json');

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'GET':
        getmethod();
        break;

    default:
        http_response_code(404); // Not Found
        echo '{"name": "data not found"}';
        break;
}

// Data get part
function getmethod()
{
    include "index.php";
    $zid = isset($_GET['zid']) ? $_GET['zid'] : die();

    $sql = "SELECT xcode, xlong FROM xcodes WHERE zid = $zid AND xtype = 'Machine No' AND zactive = '1'";
       
    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        http_response_code(500); // Internal Server Error
        die(print_r(sqlsrv_errors(), true));
    }

    $rows = array();

    while ($r = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $rows[] = $r;
    }

    if (empty($rows)) {
        http_response_code(204); // No Content
    } else {
        echo json_encode($rows);
        http_response_code(200); // OK
    }

    sqlsrv_free_stmt($stmt);
}
?>
