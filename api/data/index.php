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
    $controller2 = new InternetData;
    date_default_timezone_set('Africa/Lagos');
            


    // -------------------------------------------------------------------
    //  Check Request Method
    // -------------------------------------------------------------------

    $requestMethod = $_SERVER["REQUEST_METHOD"]; 
    if ($requestMethod !== 'POST') {
        $response["status"] = "fail";
        $response["msg"] = "Only POST method is allowed";
        header('HTTP/1.0 400 Bad Request');
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
            $response["status"] = "fail";
            $response["msg"] = "Authorization token not found $token";
            header('HTTP/1.0 401 Unauthorized');
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
        
        // tell the user no products found
        $response["status"] = "fail";
        $response["msg"] = "Your authorization token is required.";
        header('HTTP/1.0 401 Unauthorized');
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
    if(isset($body->Ported_number)){$body2["ported_number"]=$body->Ported_number;}
    if(isset($body->mobile_number)){$body2["phone"]=$body->mobile_number;}
    if(isset($body->plan)){$body2["data_plan"]=$body->plan;}
    if(!isset($body->ref)){$body2["ref"]="DATA_".mt_rand(100,999).time();}
    $body = (object) array_merge( (array)$body, $body2 );

    $network= (isset($body->network)) ? $body->network : "";
    $phone= (isset($body->phone)) ? $body->phone : "";
    $phone=str_replace(" ","",$phone);
    $ported_number= (isset($body->ported_number)) ? $body->ported_number : "false";
    $data_plan= (isset($body->data_plan)) ? $body->data_plan : "";
    $ref= (isset($body->ref)) ? $body->ref : "";

    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($data_plan == ""){$requiredField ="Data Plan ID Is Required"; }
    if($phone == ""){$requiredField ="Phone Is Required"; }
    if($network == ""){$requiredField ="Network Id Required"; }
    if($ref == ""){$requiredField ="Ref Is Required"; }
    

    if($requiredField <> ""){
        $response['status']="fail";
        $response['msg'] = $requiredField;
        header('HTTP/1.0 400 Parameters Required');
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Network Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifyNetworkId($network);
    if($result["status"]=="fail"){
        $response['status']="fail";
        $response['msg'] = "The Network id is invalid";
        header('HTTP/1.0 400 Invalid Network Id');
        echo json_encode($response);
        exit();
    }
    else{
        $networkDetails=$result; 
    }


    // -------------------------------------------------------------------
    //  Check If Network Is Available
    // -------------------------------------------------------------------
    
    if($networkDetails["networkStatus"] <> "On"){
        $response['status']="fail";
        $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
        header('HTTP/1.0 400 Network Not Available');
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    //  Verify Plan Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifyDataPlanId($network,$data_plan,$usertype);
    if($result["status"]=="fail"){
        $response['status']="fail";
        $response['msg'] = "The Data Plan ID : $data_plan is invalid ";
        header('HTTP/1.0 400 Invalid Data Plan Id');
        echo json_encode($response);
        exit();
    }
    else{

        // -------------------------------------------------------------------
        //Check If SME, Gifting, Corporate Data Is Disabled
        // -------------------------------------------------------------------
       
        $datagroup = $result["datatype"];
        $actualPlanId = $result["dataplan"];
        $datagroupmessage = "";
        if($datagroup == "SME" && $networkDetails["smeStatus"] <> "On"){$datagroupmessage="Sorry, {$networkDetails["network"]} SME is not available at the moment"; }
        if($datagroup == "Gifting" && $networkDetails["giftingStatus"] <> "On"){$datagroupmessage="Sorry, {$networkDetails["network"]} SME is not available at the moment"; }
        if($datagroup == "Share" && $networkDetails["shareStatus"] <> "On"){$datagroupmessage="Sorry, {$networkDetails["network"]} SME is not available at the moment"; }
        if($datagroup == "Corporate" && $networkDetails["corporateStatus"] <> "On"){$datagroupmessage="Sorry, {$networkDetails["network"]} SME is not available at the moment"; }
        
        if($datagroupmessage <> ""){
            $response['status']="fail";
            $response['msg'] = $datagroupmessage;
            header('HTTP/1.0 400 Data Not Available At The Moment');
            echo json_encode($response);
            exit();
        }
        
        //Calculate Profit
        $amountopay =  (float) $result["amount"]; 
        $buyprice =  (float) $result["buyprice"]; 
        $profit = $amountopay - $buyprice;
        $dataname = $result['name'];
        $plandesc = "Purchase of ".$networkDetails["network"]." ".$result['name']." ".$result['datatype']." ".$result['day']." Days Plan for phone number {$phone}"; 
    }


    // -------------------------------------------------------------------
    //  Verify Phone Number
    // -------------------------------------------------------------------
    if(strlen($phone) <> 11){
        $response['status']="fail";
        $response['msg'] = "Please enter a valid 11 digit phone number";
        header('HTTP/1.0 400 Invalid Phone Number');
        echo json_encode($response);
        exit();
    }

    if($ported_number == "false"){
        $result = $controller->verifyPhoneNumber($phone,$networkDetails["network"]);
        if($result["status"]=="fail"){
            $response['status']="fail";
            $response['msg'] = $result["msg"];
            header('HTTP/1.0 400 Invalid Phone Number');
            echo json_encode($response);
            exit();
        }
    }

    // -------------------------------------------------------------------
    //  Check Id User Balance Can Perform The Transaction
    // -------------------------------------------------------------------
    if($amountopay > $userbalance || $amountopay < 0){
            $response['status']="fail";
            $response['msg'] = "Insufficient balance fund your wallet and try again";
            header('HTTP/1.0 400 Insufficient Balance');
            echo json_encode($response);
            exit();
    }


    // -------------------------------------------------------------------
    //  Check For Api Authorization
    // -------------------------------------------------------------------
    
    $result = $controller->checkIfTransactionExist($ref);
    if($result["status"]=="fail"){
        $response['status']="fail";
        $response['msg'] = "Transaction Ref Already Exist";
        header('HTTP/1.0 400 Transaction Ref Already Exist');
        echo json_encode($response);
        exit();
    }

    // -------------------------------------------------------------------
    // Purchase Data
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------

    $servicename = "Data";
    $servicedesc = $plandesc;
    
     
    $result = $controller->checkTransactionDuplicate($servicename,$servicedesc);
    if($result["status"]=="fail"){
        $response['status']="fail";
        $response['msg'] = "Possible Transaction Duplicate, Please Verify Transaction & Try Again After 30 Seconds";
        header('HTTP/1.0 400 Possible Transaction Duplicate, Please Verify Transaction & Try Again After 30 Seconds');
        echo json_encode($response);
        exit();
    }


    // Debit User Before Performing The Transaction To Ensure Loss Is Not Aquired On Timeout Error Or Slow Connection
    $checkDebit=$controller->debitMyUserBeforeTransaction($userid,$amountopay,$servicename,$servicedesc,$body->ref,"5");

    if($checkDebit <> "success"){
        $response['status']="fail";
        $response['msg'] = "Could Not Complete Transaction";
        header('HTTP/1.0 400 Could Not Complete Transaction');
        echo json_encode($response);
        exit();
    }

    //If Key Manual Or MM Exists, Process As Manul Data
    if(strpos($dataname,"Manual") !== false){
        $response['status']="processing";
        $response['Status']="processing";
        header('HTTP/1.0 200 Transaction Processing');
        echo json_encode($response);
        exit();
    }
    
    if(strpos($dataname,"MM") !== false){
        $response['status']="processing";
        $response['Status']="processing";
        header('HTTP/1.0 200 Transaction Processing');
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
    //  Send Request To Purchase Data
    // -------------------------------------------------------------------
    $result = $controller2->purchaseData($body,$networkDetails,$datagroup,$actualPlanId);
     
    // -------------------------------------------------------------------
    // Debit User Wallet & Record Transaction
    // -------------------------------------------------------------------
     
    if($result["status"]=="success"){

        $response['status']="success";
        $response['Status']="successful";

        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }

        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"0",$profit,$serverlog);
        if(isset($result["true_response"])){$controller->updateTransactionWithRealResponse($body->ref,$result["true_response"]);}
        if(isset($result["true_response"])){$response['true_response']= $result["true_response"]; }
       
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
        if(isset($result["true_response"])){$controller->updateTransactionWithRealResponse($body->ref,$result["true_response"]);}
        if(isset($result["true_response"])){$response['true_response']= $result["true_response"]; }
        
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