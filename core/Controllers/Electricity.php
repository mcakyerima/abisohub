<?php

    class Electricity extends ApiAccess{
        

         // ------------------------------------------------------------------------------
        // Electricity Bills Payment
        // ------------------------------------------------------------------------------

        //Verify Meter Number
		public function validateMeterNumber($body,$electricityid,$provider){

			$response = array();
            $details=$this->model->getApiDetails();
            
            //Get Ap Details
            $host = self::getConfigValue($details,"meterVerificationProvider");
            $apiKey = self::getConfigValue($details,"meterVerificationApi");
            
            // ------------------------------------------
            //  Verify Meter No
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
                "billersCode": "'.$body->meternumber.'",
                "serviceID": "'.$electricityid.'",
                "type": "'.$body->metertype.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $apiKey",
                'Content-Type: application/json'
            ),
            ));

            $exereq = curl_exec($curl);
            $err = curl_error($curl);
            
            if($err){
                $response["status"] = "fail";
                $response["msg"] = "Server Connection Error";
                file_put_contents("meter_ver_error_log.txt",json_encode($response).$err);
                curl_close($curl);
                return $response;
            }
 
            $result=json_decode($exereq);
            curl_close($curl);
            
           
            if (isset($result->content->Customer_Name)) {
                $response["status"] = "success";
                $response["msg"] = $result->content->Customer_Name;
                $response["others"] = $result;
            }
            elseif(isset($result->name)){
                $response["status"] = "success";
                $response["msg"] = $result->name;
                $response["others"] = $result;
            }
            elseif(isset($result->message->details->customer_name)){
                $response["status"] = "success";
                $response["msg"] = $result->message->details->customer_name;
                $response["others"] = $result;
            }
            else{
                $response["status"] = "fail";
                file_put_contents("meter_ver_error_log.txt",json_encode($result)." : ".$result);
            }

            return $response;
		}


        //Purchase Electricity Unit
		public function purchaseElectricityToken($body,$electricityid,$provider){

			
            $response = array();
            $details=$this->model->getApiDetails();
            
            //Get Ap Details
            $host = self::getConfigValue($details,"meterProvider");
            $apiKey = self::getConfigValue($details,"meterApi");

           
            // ------------------------------------------
            //  Purchase Electricity
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
                "request_id": "'.$body->ref.'",
                "serviceID": "'.$electricityid.'",
                "billersCode": "'.$body->meternumber.'",
                "variation_code": "'.$body->metertype.'",
                "amount": "'.$body->amount.'",
                "phone": "'.$body->phone.'"
            }',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic $apiKey",
                'Content-Type: application/json'
            ),
            ));

            $exereq = curl_exec($curl);

            $err = curl_error($curl);
            
            if($err){
                $response["status"] = "fail";
                $response["msg"] = "Server Connection Error: ".$err;
                $response["api_response_log"]=json_encode($response)." : ".$err;
                file_put_contents("meter_purchase_connect_error_log.txt",json_encode($response));
                curl_close($curl);
                return $response;
            }

            $result=json_decode($exereq);
            curl_close($curl);

            $tokenApi = array();

            if (isset($result->mainToken)) {
                $tokenApi["status"] = "success";
                $msg = "";

                if (isset($result->mainToken)) {
                    $msg .= "Token: " . $result->mainToken . "\n";
                }
                if (isset($result->content->transactions->product_name)) {
                    $msg .= "Product Name: " . $result->content->transactions->product_name . "\n";
                }
                if (isset($result->content->transactions->amount)) {
                    $msg .= "Amount: " . $result->content->transactions->amount . "\n";
                }
                if (isset($result->content->transactions->created_at)) {
                    $msg .= "Date Created: " . $result->content->transactions->created_at . "\n";
                }

                // Trim the trailing newline character
                $msg = trim($msg);

                $tokenApi["msg"] = $msg;
            }

            //Log API Response To Database
            $response["api_response_log"]=$tokenApi;

            //Get API Status
            if(isset($result->Status)){$apiStatus = strtolower($result->Status);}
            elseif(isset($result->status)){$apiStatus = strtolower($result->status);}
            else{$apiStatus = "";}

            if(isset($result->mainToken)){
                $response["status"] = "success";
                $response["msg"] = $result->mainToken;
                if(isset($result->message->details->customerName)){$response["customerName"] = $result->message->details->customerName;}
                if(isset($result->message->details->customerAddress)){$response["customerAddress"] = $result->message->details->customerAddress;}
            }
            elseif(isset($result->message->description->details->token)){
                $response["status"] = "success";
                $response["msg"] = $result->message->description->details->token;
                if(isset($result->message->description->details->customerName)){$response["customerName"] = $result->message->description->details->customerName;}
                if(isset($result->message->description->details->customerAddress)){$response["customerAddress"] = $result->message->description->details->customerAddress;}
            }
            elseif(isset($result->message->description->details->CreditToken)){
                $response["status"] = "success";
                $response["msg"] = $result->message->description->details->CreditToken;
                if(isset($result->message->description->details->customerName)){$response["customerName"] = $result->message->description->details->customerName;}
                if(isset($result->message->description->details->customerAddress)){$response["customerAddress"] = $result->message->description->details->customerAddress;}
            }
            elseif($apiStatus == 'successful' || $apiStatus == 'success'){
                $response["status"] = "success";
                $response["msg"] = $result->token;
            }
            elseif($apiStatus == 'failed' || $apiStatus == 'fail'){
                $response["status"] = "fail";
                $response["msg"] = "Transaction Failed, Please Try Again Later";

                //If Server Returns Message, Capture It If Message Is Not About A Low Wallet Balance
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
                file_put_contents("meter_fail_log.txt",json_encode($result));
            }
            elseif($apiStatus == 'processing' || $apiStatus == 'process'){
                $response["status"] = "processing";
                file_put_contents("meter_processing_log.txt",json_encode($result));
            }
            elseif($apiStatus == 'pending'){
                $response["status"] = "processing";
                file_put_contents("meter_processing_log.txt",json_encode($result));
            }
            else{
                $response["status"] = "fail";
                $response["msg"] = "Transaction Failed, Please Try Again Later";
                //Log Error On Server
                file_put_contents("meter_fail_log.txt",json_encode($result));
            }

            return $response;
		}


    }

?>