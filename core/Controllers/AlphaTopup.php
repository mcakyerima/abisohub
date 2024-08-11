<?php

    class AlphaTopup extends ApiAccess{

        //Purchase Airtime
		public function purchaseAlphaTopup($body){

            $details=$this->model->getApiDetails();
            $response = array();
 
            $host = self::getConfigValue($details,"alphaProvider");
            $apiKey = self::getConfigValue($details,"alphaApi"); 
            $serverhost=$_SERVER["SERVER_NAME"];
            
            //Check If Server Is The API Provider
            if(strpos($host, $serverhost) !== false){
                $response["status"] = "success";
                $response["apiAccessMethod"] = "provider";
                return $response;
            }else{$response["apiAccessMethod"] = "consumer"; }

           
            // ------------------------------------------
            //  Purchase Airtime
            // ------------------------------------------
            
            switch($body->amount){
                case "500": $planid = 9; break;
                case "1000": $planid = 10; break;
                case "1500": $planid = 11; break;
                case "2000": $planid = 12; break;
                case "2500": $planid = 13; break;
                case "3000": $planid = 14; break;
                case "3500": $planid = 15; break;
                case "4000": $planid = 16; break;
                default: $planid = ""; break;
            }
            
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
                "amount": "'.$body->amount.'",
                "phone": "'.$body->phone.'",
                "planid": "'.$planid.'",
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
                file_put_contents("alpha_airtime_error_log2.txt",json_encode($response).$err);
                curl_close($curl);
                return $response;
            }

            $result=json_decode($exereq);
            curl_close($curl);

             //Log API Response To Database
             $response["api_response_log"]=$exereq;

            if($result->status=='success' || $result->status=='processing'){
                $response["status"] = "success";
            }
            else{
                $response["status"] = "fail";
                $response["msg"] = "Server/Network Error";
                file_put_contents("alpha_airtime_error_log.txt",json_encode($result));
            }

            return $response;
		}

        
    }

?>