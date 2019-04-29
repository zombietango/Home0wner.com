<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/html; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
http_response_code(500);
if (!empty($_GET)) {
    $arrKeys = array_keys($_GET);
    print("<pre>" . $arrKeys[0] . " is not a known API handler</pre>");
}


?>