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

if ($states = $db->query("SELECT abbr,name FROM states")) {
    while ($row = $states->fetch_object()) {
        $stateArray[$row->abbr] = $row->name;
    }
    $db->close();
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($stateArray);
} else {
    // set response code - 404 OK
    http_response_code(404);
}
?>