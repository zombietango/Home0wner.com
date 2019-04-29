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

if (!isset($_GET['listing'])) {
    $db->close();
    http_response_code(404);
} else {
    if ($authHeader = getBearerToken()) {
        $tokenPayload = decodeJWT($authHeader);
        $getSaves = "SELECT COUNT(1) as c FROM saved_homes WHERE user_id = " . $tokenPayload->{"id"} . " AND listing_id = " . $_GET['listing'];
        if ($saves = $db->query($getSaves)) {
            $count = $saves->fetch_object()->c;
            http_response_code(200);
            if ($count == 0) {
                print("false");
            } else {
                print("true");
            }
        } else {
            http_response_code(500);
        }
        $db->close();
    } else {
        http_response_code(401);
    }
}


?>
