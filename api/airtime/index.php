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
    $airtimeController = new Airtime;
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
    if(isset($body->Ported_number)){$body2["ported_number"]=$body->Ported_number;}
    if(isset($body->mobile_number)){$body2["phone"]=$body->mobile_number;}
    if(!isset($body->ref)){$body2["ref"]="AIRTIME_".rand(100,999).time();}
    if(!isset($body->airtime_type)){$body2["airtime_type"]="VTU";}
    $body = (object) array_merge( (array)$body, $body2 );

    $network= (isset($body->network)) ? $body->network : "";
    $phone= (isset($body->phone)) ? $body->phone : "";
    $phone=str_replace(" ","",$phone);
    $amount= (isset($body->amount)) ? $body->amount : "";
    $ported_number= (isset($body->ported_number)) ? $body->ported_number : "false";
    $airtime_type= (isset($body->airtime_type)) ? $body->airtime_type : "";
    $ref= (isset($body->ref)) ? $body->ref : "";

    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($airtime_type == ""){$requiredField ="Airtime Type Is Required"; }
    if($amount == ""){$requiredField ="Amount Is Required"; }
    if($phone == ""){$requiredField ="Phone Is Required"; }
    if($network == ""){$requiredField ="Network Id Required"; }
    if($ref == ""){$requiredField ="Ref Is Required"; }
    if($airtime_type <> ""){
        if(($airtime_type <> "VTU" && $airtime_type <> "Share And Sell") && ($airtime_type <> "Momo" && $airtime_type <> "Awoof")){
            $requiredField ="Airtime Type can only be VTU, Share And Sell, Momo, Or Awoof";
        }
    }

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

    if($airtime_type == "Share And Sell"){
        if($networkDetails["networkStatus"] <> "On" || $networkDetails["sharesellStatus"] <> "On"){
            header('HTTP/1.0 400 Network Not Available');
            $response['status']="fail";
            $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
            if($networkDetails["sharesellStatus"] == "Off"){
                $response['msg'] = "Sorry, {$networkDetails["network"]} Share And Sell service is not available at the moment";
            }
            echo json_encode($response);
            exit();
        }
    }
    

    if($airtime_type == "VTU"){
        if($networkDetails["networkStatus"] <> "On" || $networkDetails["vtuStatus"] <> "On"){
            header('HTTP/1.0 400 Network Not Available');
            $response['status']="fail";
            $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
            if($networkDetails["vtuStatus"] == "Off"){
                $response['msg'] = "Sorry, {$networkDetails["network"]} VTU service is not available at the moment";
            }
            echo json_encode($response);
            exit();
        }
    }

    if($airtime_type == "Momo"){
        if($networkDetails["networkStatus"] <> "On" || $networkDetails["momoStatus"] <> "On"){
            header('HTTP/1.0 400 Network Not Available');
            $response['status']="fail";
            $response['msg'] = "Sorry, {$networkDetails["network"]} is not available at the moment";
            if($networkDetails["momoStatus"] == "Off"){
                $response['msg'] = "Sorry, {$networkDetails["network"]} Momo service is not available at the moment";
            }
            echo json_encode($response);
            exit();
        }
    }
    

    // -------------------------------------------------------------------
    //  Verify Phone Number
    // -------------------------------------------------------------------

    if(strlen($phone) <> 11){
        header('HTTP/1.0 400 Invalid Phone Number');
        $response['status']="fail";
        $response['msg'] = "Please enter a valid 11 digit phone number";
        echo json_encode($response);
        exit();
    }

    if($ported_number == "false"){
        $result = $controller->verifyPhoneNumber($phone,$networkDetails["network"]);
        if($result["status"]=="fail"){
            header('HTTP/1.0 400 Invalid Phone Number');
            $response['status']="fail";
            $response['msg'] = $result["msg"];
            echo json_encode($response);
            exit();
        }
    }

    // -------------------------------------------------------------------
    //  Calculate Airtime Discount
    // -------------------------------------------------------------------
    
    $result = $controller->calculateAirtimeDiscount($network,$airtime_type,$amount,$usertype);
    $amountopay = (float) $result["discount"];
    $buyamount =  (float) $result["buyamount"]; 
    $profit = $amountopay - $buyamount;
    
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
    //  Check For Minimum And Maximum Amount Of Airtime Purchase
    // -------------------------------------------------------------------
    $airtimelimit = $controller->getSiteSettings();
    $airtimemin = (int) $airtimelimit->airtimemin;
    $airtimemax = (int) $airtimelimit->airtimemax;

    if($amount < $airtimemin){
        header("HTTP/1.0 400 Minimum airtime purchase is $airtimemin");
        $response['status']="fail";
        $response['msg'] = "Minimum airtime you can purchase is $airtimemin";
        echo json_encode($response);
        exit();
    }

    if($amount > $airtimemax){
        header("HTTP/1.0 400 Maximum airtime purchase is $airtimemax");
        $response['status']="fail";
        $response['msg'] = "Maximum airtime you can purchase is $airtimemax";
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
    // Purchase Airtime
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------

    $servicename = "Airtime";
    $servicedesc = "{$networkDetails["network"]} Airtime purchase of N{$amount} for phone number {$phone}";
    
     
    $result = $controller->checkTransactionDuplicate($servicename,$servicedesc);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Possible Transaction Duplicate, Please Verify Transaction & Try Again After 30 Seconds');
        $response['status']="fail";
        $response['msg'] = "Possible Transaction Duplicate, Please Verify Transaction & Try Again After 30 Seconds";
        echo json_encode($response);
        exit();
    }

    // Debit User Before Performing The Transaction To Ensure Loss Is Not Aquired On Timeout Error Or Slow Connection
    $checkDebit=$controller->debitMyUserBeforeTransaction($userid,$amountopay,$servicename,$servicedesc,$body->ref,"5");
    
    if($checkDebit <> "success"){
        header('HTTP/1.0 400 Could Not Complete Transaction, Please Try Again Later');
        $response['status']="fail";
        $response['msg'] = "Could Not Complete Transaction, Please Try Again Later";
        echo json_encode($response);
        exit();
    }

    //Confirm User Wallet Again In The Case Of Multiple Request Sent Within The Same Time Frame
    $currentUserData = $controller->getUserDetails($token);
    if($currentUserData["balance"] < 0){
        header('HTTP/1.0 400 Transaction Failed');
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = "Insufficient balance on multiple request detected, please fund your wallet";
        $profit = 0;
        $apiserverlog = json_encode($response); 
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"1",$profit,$apiserverlog);
        $controller->deleteTransactionDueToInsufficientBalance($body->ref);
        echo json_encode($response);
        exit();
    }

 
    // -------------------------------------------------------------------
    //  Send Request To Purchase Airtime
    // -------------------------------------------------------------------

    $result = $airtimeController->purchaseMyAirtime($body,$networkDetails);
     
    // -------------------------------------------------------------------
    // Update Transaction Status & Credit ReferalBonus Where Applicable
    // -------------------------------------------------------------------
    if($result["status"]=="success"){
        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"0",$profit,$serverlog);
        $response['status']="success";
        $response['Status']="successful";
        header('HTTP/1.0 200 Transaction Successful');
        echo json_encode($response);
        exit();
    }  
    elseif($result["status"]=="fail"){
        header('HTTP/1.0 400 Transaction Failed');
        $response['status']="fail";
        $response['Status']="failed";
        $response['msg'] = $result["msg"];
        
        /// Add api_response_log to the response if it exists
        if (isset($result["api_response_log"])) {
            // Decode the JSON response
            $api_response_array = json_decode($result["api_response_log"], true);
        
            // Check if the "response" field exists in the decoded JSON
            if (isset($api_response_array["error"])) {
                // Assign the value of "response" to $response['api_response_log']
                $response['api_response'] = $api_response_array["error"];
            }
        }

        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"1","0",$serverlog);
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