<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';

$database = new Database();
$db = $database->connect();

if ($authHeader = getBearerToken()) {
    $tokenPayload = decodeJWT($authHeader);
    $getSaves = "SELECT listing_id,my_comments FROM saved_homes WHERE user_id = " . $tokenPayload->{"id"};
    if ($saves = $db->query($getSaves)) {
        $mySaves = [];
        while ($row = $saves->fetch_object()) {
            array_push($mySaves,$row);
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
     
        // show products data in json format
        echo json_encode($mySaves);
    } else {
        // set response code - 404 OK
        http_response_code(404);
    }
} else {
    // set response code - 401 Unauthorized
    http_response_code(401);
}

?>
