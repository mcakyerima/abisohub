<?php
// Import autoloader.php from the parent directory
require_once("../../autoloader.php");

// Allowed Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Allow: POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$response = array();
$controller = new ApiAccess;

date_default_timezone_set('Africa/Lagos');

// -------------------------------------------------------------------
//  Check Request Method
// -------------------------------------------------------------------
$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod !== 'GET') {
    header('HTTP/1.0 400 Bad Request');
    $response["status"] = "fail";
    $response["msg"] = "Only GET method is allowed";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
//  Check For Api Authorization
// -------------------------------------------------------------------
if ((isset($headers['Authorization']) || isset($headers['authorization'])) || (isset($headers['Token']) || isset($headers['token']))) {

    if ((isset($headers['Authorization']) || isset($headers['authorization']))) {
        $token = trim(str_replace("Token", "", (isset($headers['Authorization'])) ? $headers['Authorization'] : $headers['authorization']));
    }

    if ((isset($headers['Token']) || isset($headers['token']))) {
        $token = trim(str_replace("Token", "", (isset($headers['Token'])) ? $headers['Token'] : $headers['token']));
    }

    if ($token !== date("Ymd")) {
        header('HTTP/1.0 401 Unauthorized');
        $response["status"] = "fail";
        $response["msg"] = "Authorization token not found $token";
        echo json_encode($response);
        exit();
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
    $response["status"] = "fail";
    $response["msg"] = "Your authorization token is required.";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
//  Get The Request Details
// -------------------------------------------------------------------

// Get the requested URI
$uri = $_SERVER['REQUEST_URI'];

// Split the URI into segments
$uriSegments = explode('/', trim($uri, '/'));

// Plan ID and Network ID are optional, so let's check if they are provided
$planId = isset($uriSegments[4]) ? (int) $uriSegments[4] : null;
$networkId = isset($uriSegments[6]) ? (int) $uriSegments[6] : null;

// Call the controller method with the parameters
$result = $controller->getAirtimePlans($planId, $networkId);

// $result["planId"] = $planId;
// $result["networkId"] = $networkId;
// $result["uri"] = $uriSegments;

// Send JSON response
header('Content-Type: application/json');
echo json_encode($result);
exit();
