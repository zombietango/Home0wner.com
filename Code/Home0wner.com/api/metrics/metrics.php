<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../includes/Database.php';
include_once '../includes/functions.php';

class MetricsClass {

    function __construct($page,$queryString,$ipAddress,$referer,$userAgent,$os) {
        $this->page = $page;
        $this->queryString = $queryString;
        $this->ipAddress = $ipAddress;
        $this->referer = $referer;
        $this->userAgent = $userAgent;
        $this->os = $os;
    }

    function __destruct() {
        $database = new Database();
        $db = $database->connect();
        $sql = "INSERT INTO metrics (page,querystring,ipAddress,referer,userAgent,os) VALUES (?,?,?,?,?,?)";
        $recordMetrics = $db->prepare($sql);
        $recordMetrics->bind_param("ssssss",$this->page,$this->queryString,$this->ipAddress,$this->referer,$this->userAgent,$this->os);
        $recordMetrics->execute();
        http_response_code(201);
    }
}

class MetricsDebug {

    function __construct($fileName,$logContents) {
        $this->fileName = $fileName;
        $this->logContents = $logContents;
    }

    function __destruct() {
        file_put_contents($this->fileName,$this->logContents);
    }
}

$normObject = base64_decode($_POST['m']);
//print $normObject;
$metrics = unserialize($normObject);



?>