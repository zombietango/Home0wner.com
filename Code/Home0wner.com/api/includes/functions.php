<?php

function yesNo(&$value) {
    $value = $value == 1 ? 'Yes' : 'No';
    return $value;
}

function generateJWT($JWTalg,$HMACalg,$userPayload) {
    $header = json_encode(['typ' => 'JWT', 'alg' => $JWTalg]);
    $payload = json_encode($userPayload);
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $signature = hash_hmac($HMACalg, $base64UrlHeader . "." . $base64UrlPayload, 'home0wner', true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    return $jwt;
}

function decodeJWT($JWT) {
    list($header,$payload,$sig) = explode(".",$JWT);
    $h = json_decode(base64_decode($header));
    $p = json_decode(base64_decode($payload));

    if ($h->{'alg'} == "MD5") {
        $alg = "md5";
        $thisSig = hash_hmac($alg, $header . "." . $payload, 'home0wner', true);
        $b64ThisSig = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($thisSig));
        if ($b64ThisSig == $sig) {
            return $p;
        } else {
            return false;
        }
    } elseif ($h->{'alg'} == "HS256") {
       $alg = "sha256";
       $thisSig = hash_hmac($alg, $header . "." . $payload, 'home0wner', true);
        $b64ThisSig = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($thisSig));
        if ($b64ThisSig == $sig) {
            return $p;
        } else {
            return false;
        }
    } else {
        return $p;
    }

}

function getAuthorizationHeader(){
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}
/**
* get access token from header
* */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}
?>