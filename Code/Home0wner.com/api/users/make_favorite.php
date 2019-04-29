<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';

$database = new Database();
$db = $database->connect();

if (!isset($_GET['listing'])) {
    $db->close();
    http_response_code(404);
} else {
    if ($authHeader = getBearerToken() && isset($_POST['listing_id'])) {
        $tokenPayload = decodeJWT($authHeader);
        $sql = "INSERT INTO saved_homes (listing_id,user_id) VALUES (?,?)";
        $saveHome = $db->prepare($sql);
        $saveHome->bind_param("dd",$_POST['listing_id'],$tokenPayload->{"id"});
        $saveHome->execute();
        http_response_code(200);
    } else {
        http_response_code(404);
    }
}

?>