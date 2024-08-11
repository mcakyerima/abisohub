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
    $controller2 = new Smile;
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
    
    if(isset($headers['Authorization']) || isset($headers['authorization'])){
        $token = trim(str_replace("Token", "", (isset($headers['Authorization'])) ? $headers['Authorization'] : $headers['authorization']));
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
            $userbalance = (int) $result["balance"]; 
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
   
   

    $PhoneNumber= (isset($body->PhoneNumber)) ? $body->PhoneNumber : "";
    $BundleTypeCode= (isset($body->BundleTypeCode)) ? $body->BundleTypeCode : "";
    $actype= (isset($body->actype)) ? $body->actype : "";
    $ref= (isset($body->ref)) ? $body->ref : "";

    // -------------------------------------------------------------------
    //  Check Inputs Parameters
    // -------------------------------------------------------------------

    $requiredField = "";
    
    if($BundleTypeCode == ""){$requiredField ="Data BundleTypeCode ID Is Required"; }
    if(!is_numeric($BundleTypeCode)){$requiredField ="Data BundleTypeCode ID Is Required"; }
     if(!is_numeric($PhoneNumber)){$requiredField ="Only number is allow for Phone Number or Account Number"; }
     if($actype == "PhoneNumber" AND strlen($PhoneNumber)<13 || strlen($PhoneNumber)>13 ){$requiredField ="Minimum and Maximum Phone Number is 13 digits including 234"; }
     if($actype == "AccountNumber" AND strlen($PhoneNumber)<10 || strlen($PhoneNumber)>10 ){$requiredField ="Minimum and Maximum Account Number  is 10 digits"; }
    if($PhoneNumber == ""){$requiredField ="Phone Number or Account Number Is Required"; }
    if($actype == ""){$requiredField ="Account Type Is Required"; }
    
    if($ref == ""){$requiredField ="Ref Is Required"; }
    

    if($requiredField <> ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit();
    }


    // -------------------------------------------------------------------
    //  Verify Plan Id
    // -------------------------------------------------------------------
    
    $result = $controller->verifySmileDataPlanId($BundleTypeCode,$usertype);
    if($result["status"]=="fail"){
        header('HTTP/1.0 400 Invalid Data Plan Id');
        $response['status']="fail";
        $response['msg'] = "Invalid Data Plan Id";
        echo json_encode($response);
        exit();
    }
    else{

        //percenatage
        $smilediscount = $result["smilediscount"];
        $amount = $result["amount"];
        $per = $result["amount"]/100 * $smilediscount;
        $amountopay = $result["amount"] - $per; 
        $plandesc = "Puchase of SMILE ".$result['description']." ".$result['validity']."  Plan for phone number {$PhoneNumber}"; 
        $plandesc2 = "Puchase of SMILE ".$result['description']; 
    }


    // -------------------------------------------------------------------
    //  Check Id User Balance Can Perform The Transaction
    // -------------------------------------------------------------------
    if($amount > $userbalance || $amount<0 ){
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

    $result = $controller2->purchaseSmileData($body);
    
    // -------------------------------------------------------------------
    // Debit User Wallet & Record Transaction
    // -------------------------------------------------------------------
    $servicename = "Smile";
    $servicedesc = $plandesc;
    
     file_put_contents("result.txt",json_encode($result['status']));
   
    if($result['status']=="success"){
        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }
        $controller->creditSmileBonus($userid,$amount,$servicename,$servicedesc);
        
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"0","0",$serverlog);

        $response['status']="success";
        $response['network']="SMILE";
        $response['amount']=$result["amount"];
        $response['oldbal']=$result["oldbal"];
        $response['newbal']=$result["newbal"];
        $response['service']=$servicename;
        $response['description']=$plandesc2;
        header('HTTP/1.0 200 Transaction Successful');
        echo json_encode($response);
        exit(); 
    }
    elseif($result['status']=="processing"){
        if($refearedby <> ""){ $controller->creditReferalBonus($referal,$referalname,$refearedby,$servicename); }
        $controller->creditSmileBonus($userid,$amount,$servicename,$servicedesc);
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$amountopay,"2","0",$serverlog);
        $response['status']="success";
        $response['network']="SMILE";
        $response['amount']=$result["amount"];
        $response['oldbal']=$result["oldbal"];
        $response['newbal']=$result["newbal"];
        $response['service']=$servicename;
        $response['description']=$plandesc2;
        header('HTTP/1.0 200 Transaction Successful');
        echo json_encode($response);
        exit(); 
    }
    else{
        header('HTTP/1.0 400 Transaction Failed');
        $response['status']="fail";
        $response['msg']=$result['msg'];
        if(isset($result["api_response_log"])){$serverlog = $result["api_response_log"]; } else{$serverlog = ""; }
        $controller->updateMyTransactionStatus($userid,$body->ref,$userbalance,$amountopay,"1","0",$serverlog);
        echo json_encode($response);
        exit();
    }

?>