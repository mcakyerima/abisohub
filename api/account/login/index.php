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
    $controller = new Account;
   
    date_default_timezone_set('Africa/Lagos');
            


    // -------------------------------------------------------------------
    //  Check Request Method
    // -------------------------------------------------------------------

    $requestMethod = $_SERVER["REQUEST_METHOD"]; 
    if ($requestMethod !== 'POST') {
        header('HTTP/1.0 400 Bad Request');
        $response["status"] = "fail";
        $response["msg"] = "Only POST method is allowed";
        echo json_encode($response); exit(); 
    } 
    
    // -------------------------------------------------------------------
    //  Check For Api Authorization
    // -------------------------------------------------------------------
    
    if((isset($headers['Authorization']) || isset($headers['authorization'])) || (isset($headers['Token']) || isset($headers['token']))){
        
        if((isset($headers['Authorization']) || isset($headers['authorization']))){
            $token = trim(str_replace("Token", "", (isset($headers['Authorization'])) ? $headers['Authorization'] : $headers['authorization']));
        }
        
        if((isset($headers['Token']) || isset($headers['token']))){
            $token = trim(str_replace("Token", "", (isset($headers['Token'])) ? $headers['Token'] : $headers['token']));
        }
        
        
        if($token <> date("Ymd")){
            //Tell the user no products found
            header('HTTP/1.0 401 Unauthorized');
            $response["status"] = "fail";
            $response["msg"] = "Authorization token not found $token";
            echo json_encode($response); exit(); 
        }
        
    }
    else{
        header('HTTP/1.0 401 Unauthorized');
        // tell the user no products found
        $response["status"] = "fail";
        $response["msg"] = "Your authorization token is required.";
        echo json_encode($response); exit(); 
    }

    // -------------------------------------------------------------------
    //  Get The Request Details
    // -------------------------------------------------------------------
     
    $input = @file_get_contents("php://input");
    //decode the json file
    $body = json_decode($input);


    
    // Support Other API Format
    $phone= (isset($body->phone)) ? $body->phone : "";
    $accesspass= (isset($body->accesspass)) ? $body->accesspass : "";
    
    $phone = strip_tags($phone); $phone = filter_var($phone, FILTER_SANITIZE_STRING);
    $accesspass = strip_tags($accesspass); $accesspass = filter_var($accesspass, FILTER_SANITIZE_STRING);
    
   
    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($phone == ""){$requiredField ="Phone Number Field Is Required"; }
    if($accesspass == ""){$requiredField ="Password Field Is Required"; }
    if(!is_numeric($phone)) {$requiredField ="Please Enter A Valid Phone Number";}
    if(strlen($phone) != 11){$requiredField ="Please Enter A Valid Phone Number";}

    if($requiredField <> ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="unauthorized";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Details
    // -------------------------------------------------------------------
    
    

    $result = $controller->loginUserFingerPrint($phone,$accesspass);
    if($result["status"] == 0){
        header('HTTP/1.0 200 Success');
        $response['status']="success";
        $response['msg'] = "Login Successfull";
        $response['name'] = $result["name"];
        $response['phone'] = $result["phone"];
        $response['apiKey'] = $result["apiKey"];
        $response["userId"] = $result["userId"];
        echo json_encode($response);
        exit();
    }
    elseif($result["status"] == 1){
        header('HTTP/1.0 200 Success');
        $response['status']="invalid";
        $response['msg'] = "Incorrect Details";
        echo json_encode($response);
        exit();
    }
    elseif($result["status"] == 2){
        header('HTTP/1.0 200 Success');
        $response['status']="blocked";
        $response['msg'] = "Account Blocked, Please Contact Admin";
        echo json_encode($response);
        exit();
    }
     else{
        header('HTTP/1.0 500 Unauthorized');
        $response['status']="unauthorized";
        $response['msg'] = "Unauthorized Access";
        echo json_encode($response);
        exit();
    }
    
?>