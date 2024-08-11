<?php
    
    //KUDA API WEBHOOK NOTIFICATION
    
    //Auto Load Classes
    require_once("../autoloader.php");
    require_once("../../core/helpers/vendor/autoload.php");
    header('Content-Type: application/json');
    date_default_timezone_set('Africa/Lagos');
    
    $headers = getallheaders();
    $response = array();
    $controller = new ApiAccess;
    
    $input = @file_get_contents("php://input");
    $res = json_decode($input);
    
    //Check Notification Usernmae & Password
    $username = (isset($headers["Username"])) ? $headers["Username"] : "";
    $password = (isset($headers["Password"])) ? $headers["Password"] : "";
    $amount = (isset($res->amount)) ? $res->amount : "";
    $accountNumber = (isset($res->accountNumber)) ? $res->accountNumber : "";
    $transactionReference = (isset($res->transactionReference)) ? $res->transactionReference : "";
    
    //Check Again
    if($username == "" || $password == ""):
        $username = (isset($headers["username"])) ? $headers["username"] : "";
        $password = (isset($headers["password"])) ? $headers["password"] : "";
    endif;
    
    //Check Username & Password
    if($username == "" || $password == ""):
        echo "UnAutorized"; http_response_code(401); exit();
    endif;
    
    //Check Account Number And Amount
    if($amount == "" || $accountNumber == ""):
        echo "UnAutorized"; http_response_code(401); exit();
    endif;
     
    //Verify The Provided Username & Password
    
    $check= $controller->verifyKudaNotification($username,$password,$accountNumber);
    
    if($check->status == "success"):
            
            $userid = $check->userid;
            $userbalance = $check->balance;
            $email = $check->useremail;
            $charges = (float) $check->charges;
            $chargestype = $check->chargestype;
            
            //Convert Amount From Kobo To Naira
            $amountkobo = $amount;
            $amount = (float) $amount;
            $amount = $amount / 100;
            
            
            if($chargestype == "flat"): 
                $amounttosave = $amount - $charges;
                $chargesText ="N".$charges;
            else: 
                $amounttosave = $amount - ($amount * ($charges/100)); 
                $chargesText = $charges."%";
            endif;
            
            $servicename = "Wallet Topup";
            $servicedesc = "Wallet funding of N{$amount} via Kuda bank transfer with a service charges of $chargesText";
            $servicedesc.=". You wallet have been credited with N{$amounttosave}";
            $transactionReference = "KUDA_".$transactionReference;
            $result = $controller->recordKudaTransaction($userid,$servicename,$servicedesc,$amounttosave,$userbalance,$transactionReference,"0");
            $message = $servicedesc . ". Your transaction reference is $transactionReference";
            
            //Withdraw Funds From User Virtual Account
            $cheack2 = $controller->completeKudaFundingByWithdrawal($amountkobo,$email);
            //file_put_contents("kuda_complete.txt",json_encode($cheack2));
            
            //Send Email Notification
            
            $controller->sendEmailNotification($servicename,$message,$email);
            

            echo "Success";
            http_response_code(200);
            exit();

    else:
        echo "UnAutorized"; http_response_code(401); exit();
    endif;
    
?>