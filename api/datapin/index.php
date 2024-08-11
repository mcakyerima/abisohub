<?php
    //Auto Load Classes
    require_once("../autoloader.php");

    //Allowed API Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");
    header("Allow: POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");

    $headers = apache_request_headers();
    $response = array();
    $controller = new ApiAccess;
    $controller2 = new DataPin;
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
        $result=$controller->validateAccessToken($token);
        if($result["status"] == "fail"){
            // tell the user no products found
            header('HTTP/1.0 401 Unauthorized');
            $response["status"] = "fail";
            $response["msg"] = "Authorization token not found $token";
            echo json_encode($response); exit(); 
        }
        else{
            $usertype = $result["usertype"];
            $userbalance = (float) $result["balance"]; 
            $userid = $result["userid"];
            $refearedby = $result["refearedby"];
            $referal = $result["phone"];
            $referalname = $result["name"];
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
    $body2 = array();   
    if(!isset($body->ref)){$body2["ref"]="DATAPIN_".rand(100,999).time();}
    if(isset($body->card_name)){$body2["businessname"]=$body->card_name ;}
    $body = (object) array_merge( (array)$body, $body2 );

    $network= (isset($body->network)) ? $body->network : "";
    $quantity= (isset($body->quantity)) ? $body->quantity : "";
    $ported_number= (isset($body->ported_number)) ? $body->ported_number : "false";
    $data_plan= (isset($body->data_plan)) ? $body->data_plan : "";
    $ref= (isset($body->ref)) ? $body->ref : "";
    $businessname= (isset($body->businessname)) ? $body->businessname : "";

    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($data_plan == ""){$requiredField ="Data Plan ID Is Required"; }
    if($quantity == ""){$requiredField ="Quantity Is Required"; }
    if($network == ""){$requiredField ="Network Id Required"; }
    if($ref == ""){$requiredField ="Ref Is Required"; }
    if($businessname == ""){$requiredField ="Business Name Is Required"; }

    if($requiredField <> ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Network Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifyNetworkId($network);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Invalid Network Id');
        $response['status']="fail";
        $response['msg'] = "The Network id is invalid";
        echo json_encode($response);
        exit();
    }
    else{
        $networkDetails=$result; 
    }


    // -------------------------------------------------------------------
    //  Check If Network Is Available
    // -------------------------------------------------------------------
    
    if($networkDetails["datapinStatus"] <> "On"){
        header('HTTP/1.0 400 Network Not Available');
        $response['status']="fail";
        $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Plan Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifyDataPinId($network,$data_plan,$usertype);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Invalid Data Plan Id');
        $response['status']="fail";
        $response['msg'] = "The Data Plan ID : $data_plan is invalid ";
        echo json_encode($response);
        exit();
    }
    else{

        
        $datagroup = $result["datatype"];
        $actualPlanId = $result["datapin"];
        
        //Compute Amount To Pay
        $quantity = (int) $quantity;
        $amount =  (float) $result["amount"]; 
        $buyprice =  (float) $result["buyprice"]; 

        $amountopay = $amount * $quantity;
        $buyprice = $buyprice * $quantity;

        //Compute Profit
        $profit = $amountopay - $buyprice;

        $plandesc = "Purchase of $quantity data pin of ".$networkDetails["network"]." ".$result['name']." ".$result['datatype']." ".$result['day']." Days Plan At The Rate Of N{$amount}"; 
        $thedatasize=$result['name'];
    }
    


    // -------------------------------------------------------------------
    //  Check Id User Balance Can Perform The Transaction
    // -------------------------------------------------------------------
    if($amountopay > $userbalance || $amountopay < 0){
            header('HTTP/1.0 400 Insufficient Balance');
            $response['status']="fail";
            $response['msg'] = "Insufficient balance fund your wallet and try again";
            echo json_encode($response);
            exit();
    }


    // -------------------------------------------------------------------
    //  Check For Api Authorization
    // -------------------------------------------------------------------
    
    $result = $controller->checkIfTransactionExist($ref);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Transaction Ref Already Exist');
        $response['status']="fail";
        $response['msg'] = "Transaction Ref Already Exist";
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    // Purchase Data
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------

    $servicename = "Data Pin";
    $servicedesc = $plandesc;
    
     
    $result = $controller->checkTransactionDuplicate($servicename,$servicedesc);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Possible Transaction Duplicate, Please Verify Transaction & Try Again After 60 Seconds');
        $response['status']="fail";
        $response['msg'] = "Possible Transaction Duplicate, Please Verify Transaction & Try Again After 60 Seconds";
        echo json_encode($response);
        exit();
    }

    // Debit User Before Performing The Transaction To Ensure Loss Is Not Aquired On Timeout Error Or Slow Connection
    $checkDebit=$controller->debitMyUserBeforeTransaction($userid,$amountopay,$servicename,$servicedesc,$body->ref,"5");


    if($checkDebit <> "success"){
        header('HTTP/1.0 400 Could Not Complete Transaction');
        $response['status']="fail";
        $response['msg'] = "Could Not Complete Transaction";
        echo json_encode($response);
        exit();
    }

    //Confirm User Wallet Again In The Case Of Multiple Request Sent Within The Same Time Frame
    $currentUserData = $controller->getUserDetails($token);
    if($currentUserData["balance"] < 0){
       
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = "Insufficient balance on multiple request detected, please fund your wallet";
        $profit = 0;
        $apiserverlog = json_encode($response);
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"1",$profit,$apiserverlog);
        $controller->deleteTransactionDueToInsufficientBalance($body->ref);
        header('HTTP/1.0 400 Transaction Failed');
        echo json_encode($response);
        exit();
    }

   
    // -------------------------------------------------------------------
    //  Send Request To Purchase Data Pin
    // -------------------------------------------------------------------
    $result = $controller2->purchaseDataPin($body,$networkDetails,$datagroup,$actualPlanId);
     
    // -------------------------------------------------------------------
    // Debit User Wallet & Record Transaction
    // -------------------------------------------------------------------
     
    if($result["status"]=="success"){
        
        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }
        
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"0",$profit,$serverlog);

        $controller->saveDataPin($userid,$body->ref,$businessname,$networkDetails["network"],$thedatasize,$result["quantity"],$result["serial"],$result["pin"],$result["load_pin"],$result["check_balance"]);
        
        $response['status']="success";
        $response['Status']="successful";
        $response["quantity"] = $result["quantity"];
        $response["serial"] = $result["serial"];
        $response["pin"] = $result["pin"];
        $response["load_pin"] = $result["load_pin"];
        $response["check_balance"] = $result["check_balance"];

        header('HTTP/1.0 200 Transaction Successful');
        echo json_encode($response);

        exit(); 
    }
    elseif($result["status"]=="fail"){
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = $result["msg"];
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"1","0",$serverlog);
        
        header('HTTP/1.0 400 Transaction Failed');
        echo json_encode($response);
        exit();
    }
    else{
        $response['status']="processing";
        $response['Status']="processing";

        if(isset($result["api_response_log"])){ $controller->updateTransactionWithApiResponseLog($body->ref,$result["api_response_log"]); }
        
        header('HTTP/1.0 200 Transaction Processing');
        echo json_encode($response);
        exit(); 
    }

?>