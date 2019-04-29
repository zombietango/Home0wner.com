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

    $checkForPull = $db->query('SELECT id FROM credit_information WHERE user_id = ' . $tokenPayload->{"id"});
    if ($checkForPull->num_rows == 0) {
        http_response_code(404);
    } else {
        http_response_code(200);
    }
    $db->close();
} else {
    http_response_code(401);
}


?>