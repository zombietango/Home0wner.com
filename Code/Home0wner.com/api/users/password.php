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

if (!isset($_POST['password']) or !isset($_POST['user_id'])) {
    // set response code - 401 Unauthorized
    http_response_code(403);
}

$hashedPassword = md5($_POST['password']);
$changePassword = "UPDATE users SET password = ? WHERE id = ?";
$doChange = $db->prepare($changePassword);
$doChange->bind_param("sd",$hashedPassword,int($_POST['user_id']));
$doChange->execute();

if ($doChange->affected_rows > 0) {
    http_response_code(200);
} else {
    http_response_code(500);
}

?>
