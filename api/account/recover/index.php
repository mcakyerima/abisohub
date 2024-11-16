<?php
// import autoloader.php from the parent directory
require_once("../../autoloader.php");

// Allowed Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$response = array();
$controller = new AccountAccess;

date_default_timezone_set('Africa/Lagos');

// -------------------------------------------------------------------
// Check Request Method and Get Request Data
// -------------------------------------------------------------------

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === 'POST') {
    // Handle POST method: Get email and isApiRequest from JSON body
    $input = @file_get_contents("php://input");
    $body = json_decode($input, true);

    // Check if decoding was successful
    if ($body === null && json_last_error() !== JSON_ERROR_NONE) {
        header("HTTP/1.0 400 Bad Request");
        $response["status"] = "fail";
        $response["msg"] = "Invalid JSON payload";
        echo json_encode($response);
        exit();
    }

    $email = isset($body['email']) ? $body['email'] : '';
    $isApiRequest = isset($body['isApiRequest']) ? $body['isApiRequest'] : "";
} elseif ($requestMethod === 'GET') {
    // Handle GET method: Get email and isApiRequest from query parameters
    $email = isset($_GET['email']) ? $_GET['email'] : '';
    $isApiRequest = isset($_GET['isApiRequest']) ? $_GET['isApiRequest'] : "";
} else {
    header('HTTP/1.0 405 Method Not Allowed');
    $response["status"] = "fail";
    $response["msg"] = "Method not allowed. Only GET and POST are allowed.";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
// Check For API Authorization
// -------------------------------------------------------------------

if ((isset($headers['Authorization']) || isset($headers['authorization'])) || (isset($headers['Token']) || isset($headers['token']))) {

    if (isset($headers['Authorization']) || isset($headers['authorization'])) {
        $token = trim(str_replace("Token", "", $headers['Authorization'] ?? $headers['authorization']));
    } elseif (isset($headers['Token']) || isset($headers['token'])) {
        $token = trim(str_replace("Token", "", $headers['Token'] ?? $headers['token']));
    }

    if ($token !== date("Ymd")) {
        header('HTTP/1.0 401 Unauthorized');
        $response["status"] = "fail";
        $response["msg"] = "Invalid authorization token.";
        echo json_encode($response);
        exit();
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
    $response["status"] = "fail";
    $response["msg"] = "Authorization token is required.";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
// Clean and Process the Request
// -------------------------------------------------------------------

$_POST = $body;

$email = (isset($body->email)) ? $body->email : '';

$isApiRequest = (isset($body->isApiRequest)) ? $body->isApiRequest : "";

$email = $controller->cleanParameter($email, "EMAIL");
$check = $controller->recoverUserLogin($email, $isApiRequest);

header('Content-Type: application/json');
echo json_encode($check);
exit();
