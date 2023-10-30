<?php

include "index.php";

header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
  case 'GET':
    if (isset($_GET['zemail'])) {
      $zemail = $_GET['zemail'];

      $result = get_user_details($zemail);

      if ($result) {
        http_response_code(200);
        echo json_encode($result);
      } else {
        http_response_code(404);
        echo json_encode(array("message" => "User not found"));
      }
    } else {
      http_response_code(400);
      echo json_encode(array("message" => "Missing parameters"));
    }
    break;

  default:
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed"));
    break;
}

function get_user_details($zemail) {
  include "index.php";

  $sql = "SELECT xusers.zid, zbusiness.zorg FROM xusers JOIN zbusiness ON xusers.zid = zbusiness.zid WHERE xusers.zid <> 100000 and xusers.zemail = :zemail AND xusers.zactive = 1";

  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':zemail', $zemail, PDO::PARAM_STR);
  $stmt->execute();

  $user_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $user_details;
}

?>
