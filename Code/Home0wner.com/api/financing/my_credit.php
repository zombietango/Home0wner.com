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

$user_id = $_POST['user_id'];

if (count($creditData != 3)) {
    http_response_code(500);
}

if ($authHeader = getBearerToken()) {
    $tokenPayload = decodeJWT($authHeader);

    $myPullSQL = 'SELECT credit_score,income,monthly_debts,pull_date FROM credit_information WHERE user_id = ?';
    $myPull = $db->prepare($myPullSQL);
    $myPull->bind_param("d",$user_id);
    $myPull->execute();
    $result = $myPull->get_result();

    $returned = $result->num_rows;

    if ( $returned > 0 ) {
        while ($row = $result->fetch_object()) {
            $creditReturn['credit_score'] = $row->credit_score;
            $creditReturn['income'] = $row->income;
            $creditReturn['monthly_debts'] = $row->monthly_debts;
            $creditReturn['pull_date'] = $row->pull_date;
        }
        $db->close();
        // set response code - 200 OK
        http_response_code(200);
    
        // show products data in json format
        echo json_encode($creditReturn);
    } else {
        // set response code - 404 OK
        http_response_code(404);
    }
} else {
    http_response_code(401);
}

?>
