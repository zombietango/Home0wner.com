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
    $output = [];
    $output["first_name"] = $tokenPayload->{"first_name"};
    $output["last_name"] = $tokenPayload->{"last_name"};
    $output["full_name"] = $output["first_name"] . " " . $output["last_name"];
    $output["user_id"] = $tokenPayload->{"id"};
    if ($tokenPayload->{"is_admin"} == "1") {
        $output["role"] = "admin";
    } else {
        $output["role"] = "user";
    }
    $output["email"] = $tokenPayload->{"email"};
    $output["city"] = $tokenPayload->{"city"};
    $output["zip"] = $tokenPayload->{"zip_code"};

    $favSQL = "SELECT listing_id,my_comments FROM saved_homes WHERE user_id = " . $output["user_id"] . "";
    if ($favs = $db->query($favSQL)) {
        if ($favs->num_rows > 0 ) {
            while ($row = $favs->fetch_object()) {
                $output["favs"][$row->listing_id] = $row->my_comments;
            }
        }
    }
    print(json_encode($output));
} else {
    // set response code - 401 Unauthorized
    http_response_code(401);
}

?>