<?php
// import autoloader.php from the parent directory
require_once("../autoloader.php");

// Allowed Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Allow: POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$response = array();
$controller = new ApiAccess;

date_default_timezone_set('Africa/Lagos');

// -------------------------------------------------------------------
// Check Request Method and Get Request Data
// -------------------------------------------------------------------

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === 'POST') {
    // Handle POST method: get userId and limit from JSON body
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

    $userId = isset($body['userId']) ? $body['userId'] : '';
    $limit = isset($body['limit']) ? $body['limit'] : "";
} elseif ($requestMethod === 'GET') {
    // Handle GET method: get userId and limit from query parameters
    $userId = isset($_GET['userId']) ? $_GET['userId'] : '';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : "";
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
    } else if (isset($headers['Token']) || isset($headers['token'])) {
        $token = trim(str_replace("Token", "", $headers['Token'] ?? $headers['token']));
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
// Fetch Transactions
// -------------------------------------------------------------------

$result = $controller->fetchTransactionsById($userId, $limit);
header('Content-Type: application/json');
echo json_encode($result);
exit();
