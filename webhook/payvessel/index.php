<?php
    
    //PAY VESSEL API WEBHOOK NOTIFICATION
    
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
    
    $payvessel_signature = $_SERVER['HTTP_PAYVESSEL_HTTP_SIGNATURE'];
    $ip_address = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']; 
    
    $email = $res->customer->email;
    $amount = $res->order->amount;
    $transactionReference = $res->transaction->reference;
    
    //Verify The Transaction
    $check=$controller->verifyPayvesselRef($email,$payvessel_signature,$input);
   
    
    if($check->status == "success"):
            
            $userid = $check->userid;
            $userbalance = $check->balance;
            $email = $check->useremail;
            $charges = (float) $check->charges;
            $chargestype = $check->chargestype;
            $amount = (float) $amount;
           
            
            
            if($chargestype == "flat"): 
                $amounttosave = $amount - $charges;
                $chargesText ="N".$charges;
            else: 
                $amounttosave = $amount - ($amount * ($charges/100)); 
                $chargesText = $charges."%";
            endif;
            
            $servicename = "Wallet Topup";
            $servicedesc = "Wallet funding of N{$amount} via Payvessel transfer with a service charges of $chargesText";
            $servicedesc.=". You wallet have been credited with N{$amounttosave}";
            $transactionReference = "PAYVESSEL_".$transactionReference;
            $result = $controller->recordPayvesselTransaction($userid,$servicename,$servicedesc,$amounttosave,$userbalance,$transactionReference,"0");
            $message = $servicedesc . ". Your transaction reference is $transactionReference";
            
            //Send Email Notification
            
            $controller->sendEmailNotification($servicename,$message,$email);
            

            echo "Success";
            http_response_code(200);
            exit();

    else:
        echo "UnAutorized"; http_response_code(401); exit();
    endif;
    
?>