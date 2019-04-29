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

if (!isset($_GET['zip']) && !isset($_GET['city'])) {
    // set response code - 403 Forbidden
    http_response_code(403);
} elseif (isset($_GET['zip'])) {
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
        $zip = $_GET['zip'];
        $WHERE = "state_code = '$state' AND zip LIKE '$zip%'";
    } else {
        $zip = $_GET['zip'];
        $WHERE = "zip LIKE '$zip%'";
    }
    if ($cities = $db->query("SELECT city,state_code,zip FROM cities_details WHERE $WHERE ORDER BY city ASC LIMIT 5")) {
        while ($row = $cities->fetch_object()) {
            $cityArray[] = $row->city . ", " . $row->state_code . " " . $row->zip;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($cityArray);
    } else {
        // set response code - 404 OK
        http_response_code(500);
    }
} elseif (isset($_GET['city'])) {
    if (isset($_GET['state'])) {
        $state = $_GET['state'];
        $city = $_GET['city'];
        $WHERE = "state_code = '$state' AND city LIKE '%$city%'";
    } else {
        $city = $_GET['city'];
        $WHERE = "city LIKE '%$city%'";
    }
    if ($cities = $db->query("SELECT city,state_code,zip FROM cities_details WHERE $WHERE ORDER BY city ASC LIMIT 5")) {
        while ($row = $cities->fetch_object()) {
            $cityArray[] = $row->city . ", " . $row->state_code . " " . $row->zip;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($cityArray);
    } else {
        // set response code - 404 OK
        http_response_code(500);
    }
}

?>