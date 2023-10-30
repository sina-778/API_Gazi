<?php

include "index.php";

header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
  case 'GET':
    if (isset($_GET['zemail']) && isset($_GET['xpassword'])) {
      $zemail = $_GET['zemail'];
      $xpassword = $_GET['xpassword'];

      $result = get_user_details($zemail, $xpassword);

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

function get_user_details($zemail, $xpassword) {
  include "index.php";

  $sql = "SELECT ISNULL(pdmst.xname, '') AS xname, 
    ISNULL(pdmst.xstaff, '') AS xstaff,
    ISNULL((select isnull(departmentname, '') AS departmentname from pdmstview where  zid = pdmst.zid AND xstaff = pdmst.xstaff), '') AS xdeptname, 
    ISNULL(pdmst.xposition, '') AS xposition,
    ISNULL(pdmst.xempbank,'') xempbank, 
    ISNULL(pdmst.xacc, '') xacc,
    ISNULL(pdmst.xsex, '') xsex,
    ISNULL(pdmst.xempcategory, '') xempcategory,
    ISNULL(xusers.xrole, '') AS xrole, 
    ISNULL(xusers.zemail, '') AS zemail,
    ISNULL(xusers.xpassword, '') AS xpassword,
    ISNULL((select designationname from pdmstview where  zid = pdmst.zid AND xstaff = pdmst.xstaff), '') AS xdesignation,
    ISNULL(pdmst.xsid, '') AS xsid, 
    ISNULL((SELECT TOP 1 xname FROM pdmst WHERE xposition = pdmst.xsid), '') AS supname
    FROM pdmst
    JOIN xusers ON pdmst.zid = xusers.zid AND pdmst.xposition = xusers.xposition
    WHERE xusers.zid = 100000
    AND xusers.zactive = 1
    AND xusers.zactiveapp = 1
    AND xusers.zemail = :zemail
    AND xusers.xpassword = :xpassword";

  $stmt = $conn->prepare($sql);

  $stmt->bindParam(':zemail', $zemail, PDO::PARAM_STR);
  $stmt->bindParam(':xpassword', $xpassword, PDO::PARAM_STR);

  $stmt->execute();

  $user_details = array();

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $user_details = $row;
  }

  return $user_details;
}
