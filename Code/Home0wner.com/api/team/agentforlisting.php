<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';



if (!isset($_GET['listing'])) {
        // set response code - 401 Unauthorized
        http_response_code(404);
} else {
    $database = new Database();
    $db = $database->connect();

    $agentSQL = "SELECT agents.id as agentID,agents.name,agents.phone,agents.email,agents.image,listings.id AS listingID FROM listings INNER JOIN agents ON listings.listing_agent=agents.id WHERE listings.id = ?";
    $agentDetails = $db->prepare($agentSQL);
    try {
        $agentDetails->bind_param("d", $_GET['listing']);
        if ( $agentDetails === false ) {
            throw new Exception("Cannot bind on supplied listing ID");
        }
        $agentDetails->execute();
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("error" => "No agent record found for listing with id " . $_GET['listing']));
        die();
    }
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
        echo json_encode(array("errorMsg" => "No agent record found for listing with id " . $_GET['listing']));
    }

}

?>