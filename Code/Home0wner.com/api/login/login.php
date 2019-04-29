<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';

$database = new Database();
$db = $database->connect();

if (!isset($_POST['email']) or !isset($_POST['password'])) {
    // set response code - 401 Unauthorized
    http_response_code(403);
}

$hashedPassword = md5($_POST['password']);
$accountLookup = "SELECT * FROM users WHERE email = ? and password = ?";
$accountDetails = $db->prepare($accountLookup);
$accountDetails->bind_param("ss", $_POST['email'],$hashedPassword);
$accountDetails->execute();
//$accountDetails->store_result();
$result = $accountDetails->get_result();
$returned = $result->num_rows;
if ( $returned > 0 ) {
    while ($row = $result->fetch_object()) {
        $jwtPayload['email'] = $row->email;
        $jwtPayload['first_name'] = $row->first_name;
        $jwtPayload['last_name'] = $row->last_name;
        $jwtPayload['id'] = $row->id;
        $jwtPayload['zip_code'] = $row->zip_code;
        $zipLookup = $db->query("SELECT city,state_code FROM cities_details WHERE zip = '$row->zip_code'");
        while ($c = $zipLookup->fetch_object()) {
            $myLocation = $c->city . ", " . $c->state_code;
        }
        $jwtPayload['city'] = $myLocation;
        $jwtPayload['is_admin'] = $row->is_admin;
    }

    $db->close();

    $jwt = generateJWT('HS256','sha256', $jwtPayload);

    print $jwt;
    http_response_code(200);
} else {
    // set response code - 401 Unauthorized
    http_response_code(401);
}

?>