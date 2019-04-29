<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';



$database = new Database();
$db = $database->connect();

if ($agents = $db->query("SELECT * FROM agents")) {
    while ($row = $agents->fetch_object()) {
        $thisAgent[$row->id]["name"] = $row->name;
        $thisAgent[$row->id]["phone"] = $row->phone;
        $thisAgent[$row->id]["email"] = $row->email;
        $thisAgent[$row->id]["image"] = $row->image;
    }
    $db->close();
    // set response code - 200 OK
    http_response_code(200);
    
    // show products data in json format
    echo json_encode($thisAgent);
} else {
    // set response code - 404 OK
    http_response_code(404);
}

?>