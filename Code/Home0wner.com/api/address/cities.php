<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';

$database = new Database();
$db = $database->connect();

if (!isset($_GET['state'])) {
    if ($cities = $db->query("SELECT city FROM cities ORDER BY city ASC")) {
        while ($row = $cities->fetch_object()) {
            $cityArray[] = $row->city;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($cityArray);
    } else {
        // set response code - 404 OK
        http_response_code(404);
    }

} elseif (isset($_GET['state']) && strlen($_GET['state']) == 2) {
    $state = $_GET['state'];
    if ($cities = $db->query("SELECT city FROM cities WHERE state_code = '$state' ORDER BY city ASC")) {
        while ($row = $cities->fetch_object()) {
            $cityArray[] = $row->city;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($cityArray);
    } else {
        // set response code - 404 OK
        http_response_code(404);
    }
} else {
    // set response code - 403 Forbidden
    http_response_code(403);
}

?>