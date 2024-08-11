<?php

    class Cable extends ApiAccess{
        

        //Verify Cable Tv Number
		public function validateIUCNumber($body,$cableid,$provider){

			$response = array();
            $details=$this->model->getApiDetails();
            
            //Get Ap Details
            $host = self::getConfigValue($details,"cableVerificationProvider");
            $apiKey = self::getConfigValue($details,"cableVerificationApi");
             
            // ------------------------------------------
            //  Verify Cable Plan
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
                "billersCode": "'.$body->iucnumber.'",
                "serviceID": "'.$provider.'"
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
                file_put_contents("iuc_error_log2.txt",json_encode($response).$err);
                curl_close($curl);
                return $response;
            }
 
            $result=json_decode($exereq);
            curl_close($curl);
            
            
            if(isset($result->content->Customer_Name)){
                $response["status"] = "success";
                $response["msg"] = $result->content->Customer_Name;
                $response["others"] = $result;
            }
            else{
                $response["status"] = "fail";
                file_put_contents("iuc_error_log.txt",json_encode($result));
            }

            return $response;
		}

        //Purchase Cable Tv
        public function purchaseCableTv($body,$cableid,$provider,$cableplan){

			$response = array();
            $details=$this->model->getApiDetails();

            $host = self::getConfigValue($details,"cableProvider");
            $apiKey = self::getConfigValue($details,"cableApi");
           
            // ------------------------------------------
            //  Purchase Cable Plan
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
                "serviceID": "'.$provider.'",
                "billersCode": "'.$body->iucnumber.'",
                "variation_code": "'.$cableplan.'",
                "phone": "'.$body->phone.'"
            }',
            
            CURLOPT_HTTPHEADER => array(
                "Authorization: Token $apiKey",
                'Content-Type: application/json'
            ),
            ));

            $exereq = curl_exec($curl);
            $err = curl_error($curl);
            
            if($err){
                $response["status"] = "fail";
                $response["msg"] = "Server Connection Error";
                $response["api_response_log"]=json_encode($response)." : ".$err;
                file_put_contents("cable_error_log2.txt",json_encode($response).$err);
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
            
            if($apiStatus == 'successful' || $apiStatus == 'success'){
                $response["status"] = "success";
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
                file_put_contents("cabletv_fail_log.txt",json_encode($result));
            }
            elseif($apiStatus == 'processing' || $apiStatus == 'process'){
                $response["status"] = "processing";
                file_put_contents("cabletv_processing_log.txt",json_encode($result));
            }
            elseif($apiStatus == 'pending'){
                $response["status"] = "processing";
                file_put_contents("cabletv_processing_log.txt",json_encode($result));
            }
            else{
                $response["status"] = "fail";
                $response["msg"] = "Transaction Failed, Please Try Again Later";
                //Log Error On Server
                file_put_contents("cabletv_fail_log.txt",json_encode($result));
            }

            return $response;
		}


    }

?>