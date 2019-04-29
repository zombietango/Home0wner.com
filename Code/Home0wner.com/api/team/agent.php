<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';



if (!isset($_GET['agent'])) {
        // set response code - 401 Unauthorized
        http_response_code(404);
} else {
    $database = new Database();
    $db = $database->connect();

    $agentSQL = "SELECT * FROM agents WHERE id = ? ";
    $agentDetails = $db->prepare($agentSQL);
    $agentDetails->bind_param("d", $_GET['agent']);
    $agentDetails->execute();
    //$agentDetails->store_result();
    $result = $agentDetails->get_result();
    $returned = $result->num_rows;

    if ( $returned > 0 ) {
        while ($row = $result->fetch_object()) {
            $thisAgent["name"] = $row->name;
            $thisAgent["phone"] = $row->phone;
            $thisAgent["email"] = $row->email;
            $thisAgent["image"] = $row->image;
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

}

?>