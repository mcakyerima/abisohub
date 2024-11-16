<?php
// import autoloader.php from the parent directory
require_once("../../autoloader.php");
// echo "Autoloader included successfully.";


// allowed Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Allow: POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

$headers = apache_request_headers();
$response = array();
$controller = new AccountAccess;

date_default_timezone_set('Africa/Lagos');


// -------------------------------------------------------------------
//  Check Request Method
// -------------------------------------------------------------------

$requestMethod = $_SERVER["REQUEST_METHOD"];
if ($requestMethod !== 'POST') {
    header('HTTP/1.0 400 Bad Request');
    $response["status"] = "fail";
    $response["msg"] = "Only POST  method is allowed";
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


    if ($token <> date("Ymd")) {
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

$input = @file_get_contents("php://input");
//decode the json file
$body = json_decode($input, true);

// Check if decoding was successfull
if ($body == nulL && json_last_error() !== JSON_ERROR_NONE) {
    header("HTTP/1.0 400 Bad Request");
    $response["status"] = "fail";
    $response["msg"] = "Invalid JSON payload";
    echo json_encode($response);
    exit();
}

$_POST = $body;

$email = (isset($body->email)) ? $body->email : '';

$code = (isset($body->code)) ? $body->code : "";

$password = (isset($body->password)) ? $body->password : "";

$email = $controller->cleanParameter($email, "EMAIL");

$check = $controller->verifyRecoveryCode($email, $code);
if ($check == 0) {
    // change pin
    $change_pin = $controller->updateUserKey($email, $code, $password);

    if ($change_pin == 0) {
        // return success
        $response["status"] = "success";
        $response["msg"] = "Password changed successfully";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit();
    } else {
        // return error
        $response["status"] = "error";
        $response["msg"] = "An error occurred";
        header("Content-Type: application/json");
        echo json_encode($response);
        exit();
    }
} else {
    // return wrong pin
    $response["error"] = "Invalid code";
}
header('Content-Type: application/json');
echo json_encode($response);
exit();
