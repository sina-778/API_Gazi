<?php

include "index.php";

header('content-type: application/json');

$request = $_SERVER['REQUEST_METHOD'];

switch ($request) {
    case 'GET':
        getMethod();
        break;

    default:
        echo '{"name": "data not found"}';
        break;
}

// Get method to retrieve target and achievement values
function getMethod()
{
    include "index.php";
    
    $tso = isset($_GET['tso']) ? $_GET['tso'] : die();
    
    $achievementQuery = "SELECT h.zid, FLOOR(ISNULL(SUM(ISNULL(h.xtotamt, 0)), 0)) AS achievement
                         FROM opdoheader h
                         WHERE h.xstatusord = '6' AND h.xyear = YEAR(GETDATE()) AND h.xper = MONTH(GETDATE())
                         AND h.xtso = '$tso'
                         GROUP BY h.zid";
    
    $targetQuery = "SELECT t.zid, FLOOR(ISNULL(SUM(t.xtargetamt), 0)) AS target
                    FROM optargetcusheader t
                    WHERE t.xyear = YEAR(GETDATE()) AND t.xper = MONTH(GETDATE()) AND t.xtso = '$tso'
                    GROUP BY t.zid";
    
    $soQuery = "SELECT h.zid, ISNULL(COUNT(h.xsonumber), 0) AS totalSO
                FROM opsoheader h
                WHERE h.xtso = '$tso' AND MONTH(h.xdate) = MONTH(GETDATE()) AND YEAR(h.xdate) = YEAR(GETDATE())
                GROUP BY h.zid";
    
    $dpQuery = "SELECT d.zid, ISNULL(COUNT(d.xdepositnum), 0) AS totalDPnum
                FROM ardeposit d
                WHERE d.xtso = '$tso' AND MONTH(d.xdate) = MONTH(GETDATE()) AND YEAR(d.xdate) = YEAR(GETDATE())
                GROUP BY d.zid";
    
    $achievementResult = sqlsrv_query($conn, $achievementQuery);
    $targetResult = sqlsrv_query($conn, $targetQuery);
    $soResult = sqlsrv_query($conn, $soQuery);
    $dpResult = sqlsrv_query($conn, $dpQuery);

    if ($achievementResult === false || $targetResult === false || $soResult === false || $dpResult === false) {
        http_response_code(404);
        die(print_r(sqlsrv_errors(), true));
    }

    $achievements = [];
    while ($row = sqlsrv_fetch_array($achievementResult, SQLSRV_FETCH_ASSOC)) {
        $zid = $row['zid'];
        $achievement = $row['achievement'];
        $achievements[$zid] = $achievement;
    }
    
    $targets = [];
    while ($row = sqlsrv_fetch_array($targetResult, SQLSRV_FETCH_ASSOC)) {
        $zid = $row['zid'];
        $target = $row['target'];
        $targets[$zid] = $target;
    }
    
    $soNumbers = [];
    while ($row = sqlsrv_fetch_array($soResult, SQLSRV_FETCH_ASSOC)) {
        $zid = $row['zid'];
        $totalSO = $row['totalSO'];
        $soNumbers[$zid] = $totalSO;
    }
    
    $dpNumbers = [];
    while ($row = sqlsrv_fetch_array($dpResult, SQLSRV_FETCH_ASSOC)) {
        $zid = $row['zid'];
        $totalDP = $row['totalDPnum'];
        $dpNumbers[$zid] = $totalDP;
    }

    $response = [];

    // Get all unique zid values from all data
    $allZids = array_unique(array_merge(array_keys($achievements), array_keys($targets), array_keys($soNumbers), array_keys($dpNumbers)));

	
    foreach ($allZids as $zid) {
        $achievement = isset($achievements[$zid]) ? $achievements[$zid] : "0";
        $target = isset($targets[$zid]) ? $targets[$zid] : "0";
        $totalSO = isset($soNumbers[$zid]) ? $soNumbers[$zid] : "0";
        $totalDP = isset($dpNumbers[$zid]) ? $dpNumbers[$zid] : "0";
        
        $response[] = [
            'zid' => strval($zid),
            'achievement' => strval($achievement),
            'target' => strval($target),
            'totalSO' => strval($totalSO),
            'totalDPnum' => strval($totalDP)
        ];
    }

    if (empty($response)) {
        http_response_code(404);
    } else {
        echo json_encode($response);
        http_response_code(200);
    }

    sqlsrv_free_stmt($achievementResult);
    sqlsrv_free_stmt($targetResult);
    sqlsrv_free_stmt($soResult);
    sqlsrv_free_stmt($dpResult);
}




?>
