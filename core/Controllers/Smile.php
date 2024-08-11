<?php

    class Smile extends ApiAccess{
        

         //Purchase Data
		public function purchaseSmileData($body){

			$response = array();
            $details=$this->model->getApiDetails();

             
            //Get Api Key Details
            $host = self::getConfigValue($details,"smileProvider");
            $apiKey = self::getConfigValue($details,"smileApi");

            $serverhost=$_SERVER["SERVER_NAME"];
            
            //Check If Server Is The API Provider
            if(strpos($host, $serverhost) !== false){
                $response["status"] = "processing";
                return $response;
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
                "PhoneNumber": "'.$body->PhoneNumber.'",
                "BundleTypeCode": "'.$body->BundleTypeCode.'",
                "actype": "'.$body->actype.'",
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
                $response["msg"] = "Server Connection Error";
                $response["api_response_log"]=json_encode($response)." : ".$err;
                file_put_contents("smile_conn_log.txt",json_encode($response)." ".$err." ".$host);
                curl_close($curl);
                return $response;
            }

            $result=json_decode($exereq);
            curl_close($curl);
            
             //Log API Response To Database
             $response["api_response_log"]=$exereq;

            if($result->status=='success'){
                $response["status"] = "success";
                file_put_contents("smile_response_log.txt",json_encode($result));
            }
            else{
                $response["status"] = "fail";
                $response["msg"] = "Server Error";
                file_put_contents("smile_error_log.txt",json_encode($result));
            }

            return $response;
		}

        
    }

?>