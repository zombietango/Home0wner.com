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

$creditData = [];
$creditData['SSN'] = $_POST['SSN'];
$creditData['income'] = $_POST['income'];
$creditData['monthly_debts'] = $_POST['monthly_debts'];

if (count($creditData != 3)) {
    http_response_code(500);
}

if ($authHeader = getBearerToken()) {
    $tokenPayload = decodeJWT($authHeader);

    $checkForPull = $db->query('SELECT id FROM credit_information WHERE user_id = ' . $tokenPayload->{"id"});
    if ($checkForPull->num_rows == 0) {
        $myCreditScore = rand(535,820);
        $pullSQL = "INSERT INTO credit_information (user_id, credit_score, SSN, income, monthly_debts) VALUES (?,?,?,?,?)";
        $myPull = $db->prepare($pullSQL);
        $myPull->bind_param("ddsdd",$tokenPayload->{"id"},$myCreditScore,$creditData['SSN'],$creditData['income'],$creditData['monthly_debts']);
        $myPull->execute();
        http_response_code(200);
    } else {
        http_response_code(204);
    }
} else {
    http_response_code(401);
}
?>
