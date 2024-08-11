<?php
    //Auto Load Classes
    require_once("../autoloader.php");
    require_once("../../core/helpers/vendor/autoload.php");

    //Allowed API Headers
    $headers = apache_request_headers();
    $response = array();
    $controller = new ApiAccess;
    date_default_timezone_set('Africa/Lagos');
            
    // Retrieve the request's body and parse it as JSON

    $email = $_GET["email"];
    $reference = $_GET["reference"];
    $amount = $_GET["ama"];
    $amount = (float) urldecode(base64_decode($amount));
    
    if(!$reference){
        $msg = 'Server Error: No reference supplied'; 
        header("Location: ../../mobile/home/fund-wallet?msg=$msg");
        exit();
    }
    else{
        
        $check=$controller->verifyPaystackRef($email,$reference);
        
        if($check["status"] == "success"): 
            $userid = $check["userid"];
            $userbalance = $check["balance"];
            $charges = (float) $check["charges"];
            $servicename = "Wallet Topup";
            $servicedesc = "Wallet funding of N{$amount} via Paystack.";
            $amountFromPaystack = (float) $check["amount"];
            $amountFromPaystack=$amountFromPaystack / 100;
            if($amount == $amountFromPaystack):

                //Calculate Charges
                if($amount > 2500){ $amounttosave = $amountFromPaystack - ($amountFromPaystack * ($charges/100))  - 100; }
                else{ $amounttosave = $amountFromPaystack - ($amountFromPaystack * ($charges/100)); }
                
                $result = $controller->recordPaystackTransaction($userid,$servicename,$servicedesc,$amounttosave,$userbalance,$reference,"0");
                if($result == 0 ){$msg = "Wallet Funding Of N{$amounttosave} Successful. Your Transaction Reference Is $reference.";}
                else{$msg = "Transaction Reference $reference Already Exist.";}
                
                //Send Email Notification
                $controller->sendEmailNotification($servicename,$msg,$email);
                
                header("Location: ../../mobile/home/homepage?msg=$msg");
                exit();
            else:
                $msg = "Invalid Transaction. Your Transaction Reference Is $reference. Please Contact Admin For Further Assistance.";
                header("Location: ../../mobile/home/homepage?msg=$msg");
                exit();
            endif;

        else:
            $msg = "Error: " .$check["msg"];
            header("Location: ../../mobile/home/homepage?msg=$msg");
            exit();
        endif;
    }

    
		
    


?>