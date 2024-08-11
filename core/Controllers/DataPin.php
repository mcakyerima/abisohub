<?php

class DataPin extends ApiAccess{
    

     //Purchase Data
    public function purchaseDataPin($body,$networkDetails,$datagroup,$actualPlanId){

        $response = array();
        $details=$this->model->getApiDetails();
        $thenetworkId=$networkDetails["networkid"];
        //Get Api Key Details
        $host = self::getConfigValue($details,"dataPinProvider");
        $apiKey = self::getConfigValue($details,"dataPinApi");

        //Check If API Is Is Using N3TData Or Bilalsubs
        if(strpos($host, 'n3tdata247') !== false){
            $hostuserurl="https://n3tdata247.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }

        if(strpos($host, 'n3tdata') !== false){
            $hostuserurl="https://n3tdata.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }

        if(strpos($host, 'bilalsadasub') !== false){
            $hostuserurl="https://bilalsadasub.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }

        if(strpos($host, 'legitdataway') !== false){
            $hostuserurl="https://legitdataway.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }

        if(strpos($host, 'beensade') !== false){
            $hostuserurl="https://beensadeprint.com/api/user/";
            return $this->purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId);
        }
        
        // ------------------------------------------
        //  Purchase Data
        // ------------------------------------------
        
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "network": "'.$thenetworkId.'",
            "quantity": "'.$body->quantity.'",
            "data_plan": "'.$actualPlanId.'",
            "businessname": "'.$body->businessname.'",
            "ref" : "'.$body->ref.'"
        }',
        
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Token $apiKey"
        ),
        ));

        $exereq = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            $response["api_response_log"]=json_encode($response)." : ".$err;
            file_put_contents("data_error_log2.txt",json_encode($response)." ".$err." ".$host);
            curl_close($curl);
            return $response;
        }

        $result=json_decode($exereq);
        curl_close($curl);

         //Log API Response To Database
         $response["api_response_log"]=$exereq;

         //Get API Status
         if(isset($result->Status)){$apiStatus = strtolower($result->Status);}
         elseif(isset($result->status)){$apiStatus = strtolower($result->status);}
         else{$apiStatus = "";}
        

        if($apiStatus=='successful' || $apiStatus=='success'){
            $response["status"] = "success";
            $response["quantity"] = $result->quantity;
            $response["serial"] = $result->serial;
            $response["pin"] = $result->pin;
            $response["load_pin"] = $result->load_pin;
            $response["check_balance"] = $result->check_balance;
        }
        elseif($apiStatus=='successful' || $apiStatus=='success'){
            $response["status"] = "success";
            $response["quantity"] = $result->quantity;
            $response["serial"] = $result->serial;
            $response["pin"] = $result->pin;
            $response["load_pin"] = $result->load_pin;
            $response["check_balance"] = $result->check_balance;
        }
        elseif($apiStatus == 'failed' || $apiStatus == 'fail'){
            $response["status"] = "fail";
            $response["msg"] = "Transaction Failed, Please Try Again Later";

            //If Server Returns Message, Capture It If Message Is Not About A Low Wallet Balance
            if(isset($result->msg)){
                    if(strpos($result->msg, 'balance') !== false || strpos($result->msg, 'insufficient') !== false){$response["msg"] ="Unable To Complete Transaction: Please Report To Admin. Error Code BB.";}
                    else{$response["msg"] = $result->msg;}
                }

            //If Server Returns Message, Capture It If Message Is Not About A Low Wallet Balance
            if(isset($result->error[0])){
                if(strpos($result->error[0], 'balance') !== false || strpos($result->error[0], 'insufficient') !== false){$response["msg"] ="Unable To Complete Transaction: Please Report To Admin. Error Code BB.";}
                else{$response["msg"] = $result->error[0];}
            }   
            
            //If Server Returns Message, Capture It If Message Is Not About A Low Wallet Balance
            if(isset($result->message)){
                    if(strpos($result->message, 'balance') !== false || strpos($result->message, 'insufficient') !== false){$response["msg"] ="Unable To Complete Transaction: Please Report To Admin. Error Code BB.";}
                    else{$response["msg"] = $result->message;}
                }

            //Log Error On Server
            file_put_contents("datapin_fail_log.txt",json_encode($result));
        }
        elseif($apiStatus == 'processing' || $apiStatus == 'process'){
            $response["status"] = "processing";
            file_put_contents("datapin_processing_log.txt",json_encode($result));
        }
        elseif($apiStatus == 'pending'){
            $response["status"] = "processing";
            file_put_contents("datapin_processing_log.txt",json_encode($result));
        }
        else{
            $response["status"] = "fail";
            $response["msg"] = "Transaction Failed, Please Try Again Later";
            //Log Error On Server
            file_put_contents("datapin_fail_log.txt",json_encode($result));
        }

        return $response;
    }

    //Purchase Data
    public function purchaseDataWithBasicAuthentication($body,$host,$hostuserurl,$apiKey,$thenetworkId,$actualPlanId){

        $response = array();
        
        // ------------------------------------------
        //  Get User Access Token
        // ------------------------------------------
        
        
        $curlA = curl_init();
        curl_setopt_array($curlA, array(
            CURLOPT_URL => $hostuserurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $apiKey",
            ),
        ));
    
        $exereqA = curl_exec($curlA);
        $err = curl_error($curlA);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            $response["api_response_log"]=json_encode($response)." : ".$err;
            curl_close($curlA);
            return $response;
        }
        $resultA=json_decode($exereqA);
        $apiKey=$resultA->AccessToken;
        curl_close($curlA);
    
        
        // ------------------------------------------
        //  Purchase Data
        // ------------------------------------------
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $host,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "network": "'.$thenetworkId.'",
            "card_name": "'.$body->businessname.'",
            "request-id" : "'.$body->ref.'",
            "quantity": "'.$body->quantity.'",
            "plan_type": "'.$actualPlanId.'"
        }',
        
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            "Authorization: Token $apiKey"
        ),
        ));

        $exereq = curl_exec($curl);
        $err = curl_error($curl);
        
        if($err){
            $response["status"] = "fail";
            $response["msg"] = "Server Connection Error"; //.$err;
            $response["api_response_log"]=json_encode($response)." : ".$err;
            file_put_contents("basic_error_log2.txt",json_encode($response));
            curl_close($curl);
            return $response;
        }
    
        $result=json_decode($exereq);
        curl_close($curl);

         //Log API Response To Database
         $response["api_response_log"]=$exereq;
        
        if($result->status=='successful' || $result->status=='success'){
            $response["status"] = "success";
            $response["quantity"] = $result->quantity;
            $response["serial"] = $result->serial;
            $response["pin"] = $result->pin;
            $response["load_pin"] = $result->load_pin;
            $response["check_balance"] = $result->check_balance;
        }
        elseif($result->status=='fail'){
            $response["status"] = "fail";
            $response["msg"] = "Network Error, Please Try Again Later";
             file_put_contents("datapin_error_log.txt",json_encode($result));
        }
        else{
            $response["status"] = "fail";
            $response["msg"] = "Server Error: ".$result->message;
            file_put_contents("basic_data_error_log.txt",json_encode($result));
        }

        return $response;
    }
    

}

?>