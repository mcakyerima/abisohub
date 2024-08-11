<?php
    //Auto Load Classes
    require_once("../../autoloader.php");
    //Allowed API Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");
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
    if ($requestMethod !== 'POST' && $requestMethod !== 'GET') {
        header('HTTP/1.0 400 Bad Request');
        $response["status"] = "fail";
        $response["msg"] = "Only POST and GET method is allowed";
        echo json_encode($response); exit(); 
    }
    
    $result = $controller->getNumberOfAvailablePins();
    
    if(!empty($result)){
        $response['status']="success";
        $response['Status']="successful";
        $response['msg']=$result;
        header('HTTP/1.0 200 Successful');
        echo json_encode($response);
        exit(); 
    }
    else{
        header('HTTP/1.0 400 Transaction Failed');
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = "";
        echo json_encode($response);
        exit();
    }

?>