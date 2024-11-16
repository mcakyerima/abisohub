<?php
// -------------------------------------------------------------------
//  Auto Load Classes
// -------------------------------------------------------------------
require_once(__DIR__ . "/../../autoloader.php");

// -------------------------------------------------------------------
//  Allowed API Headers
// -------------------------------------------------------------------
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Allow: POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Authorization, Token, Origin, X-Requested-With, Content-Type, Accept");

// Debug Headers and Request Method
$headers = apache_request_headers();
$requestMethod = $_SERVER["REQUEST_METHOD"];
$response = array(); // Default response array for the API
$controller = new Account;

// Log headers and method for debugging
file_put_contents("debug.log", "Request Method: $requestMethod\nHeaders: " . print_r($headers, true), FILE_APPEND);

// -------------------------------------------------------------------
//  Check Request Method
// -------------------------------------------------------------------
if ($requestMethod !== 'POST') {
    header('HTTP/1.0 400 Bad Request');
    $response["status"] = "fail";
    $response["msg"] = "Only POST method is allowed";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
//  Check For API Authorization
// -------------------------------------------------------------------
$token = "";
if (!empty($headers['Authorization']) || !empty($headers['authorization'])) {
    $token = trim(str_replace(
        "Token",
        "",
        !empty($headers['Authorization']) ? $headers['Authorization'] : $headers['authorization']
    ));
} elseif (!empty($headers['Token']) || !empty($headers['token'])) {
    $token = trim(str_replace(
        "Token",
        "",
        !empty($headers['Token']) ? $headers['Token'] : $headers['token']
    ));
}

if ($token !== date("Ymd")) {
    header('HTTP/1.0 401 Unauthorized');
    $response["status"] = "fail";
    $response["msg"] = "Invalid or missing authorization token: $token";
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
//  Get The Request Details
// -------------------------------------------------------------------
$input = @file_get_contents("php://input");
$body = json_decode($input); // Decode the JSON payload

// Debug incoming payload
file_put_contents(
    'debug.log',
    "Request Method: " . $_SERVER["REQUEST_METHOD"] . PHP_EOL .
        "Headers: " . print_r(apache_request_headers(), true) . PHP_EOL .
        "Body: " . file_get_contents("php://input") . PHP_EOL .
        str_repeat('-', 80) . PHP_EOL,
    FILE_APPEND
);

// Support Other API Formats
$phone = isset($body->phone) ? $body->phone : "";
$accesspass = isset($body->accesspass) ? $body->accesspass : "";

// Sanitize Inputs
$phone = filter_var(strip_tags($phone), FILTER_SANITIZE_STRING);
$accesspass = filter_var(strip_tags($accesspass), FILTER_SANITIZE_STRING);

// -------------------------------------------------------------------
//  Validate Inputs
// -------------------------------------------------------------------
$requiredField = "";

if (empty($phone)) {
    $requiredField = "Phone Number Field Is Required";
} elseif (empty($accesspass)) {
    $requiredField = "Password Field Is Required";
} elseif (!is_numeric($phone) || strlen($phone) != 11) {
    $requiredField = "Please Enter A Valid Phone Number";
}

if (!empty($requiredField)) {
    header('HTTP/1.0 400 Parameters Required');
    $response['status'] = "fail";
    $response['msg'] = $requiredField;
    echo json_encode($response);
    exit();
}

// -------------------------------------------------------------------
//  Verify User Details
// -------------------------------------------------------------------
$result = $controller->loginUserFingerPrint($phone, $accesspass);

if ($result["status"] == 0) {
    // Successful login
    header('HTTP/1.0 200 OK');
    $response['status'] = "success";
    $response['msg'] = "Login Successful";
    $response["fname"] = $result["fname"];
    $response["lname"] = $result["lname"];
    $response["email"] = $result['email'];
    $response["phone"] = $result["phone"];
    $response["state"] = $result["state"];
    $response['apiKey'] = $result["apiKey"];
    $response["userId"] = $result["userId"];
    $response["transpin"] = $result["pin"];
} elseif ($result["status"] == 1) {
    // Incorrect details
    header('HTTP/1.0 401 Unauthorized');
    $response['status'] = "fail";
    $response['msg'] = "Incorrect Details";
} elseif ($result["status"] == 2) {
    // Blocked account
    header('HTTP/1.0 403 Forbidden');
    $response['status'] = "fail";
    $response['msg'] = "Account Blocked, Please Contact Admin";
} else {
    // General failure
    header('HTTP/1.0 500 Internal Server Error');
    $response['status'] = "fail";
    $response['msg'] = "An unexpected error occurred";
}

echo json_encode($response);
exit();
