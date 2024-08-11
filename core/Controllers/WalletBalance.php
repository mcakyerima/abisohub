<?php

    class WalletBalance extends ApiAccess{

        //Get Wallet Balance
		public function getWalletBalance($wallet,$details){

			$response = array();
            
            

            if($wallet == "one"){$name="walletOneApi"; $name2="walletOneProvider"; $name3="walletOneProviderName";}
            if($wallet == "two"){$name="walletTwoApi"; $name2="walletTwoProvider"; $name3="walletTwoProviderName";}
            if($wallet == "three"){$name="walletThreeApi"; $name2="walletThreeProvider"; $name3="walletThreeProviderName";}
            if($wallet == "four"){$name="walletFourApi"; $name2="walletFourProvider"; $name3="walletFourProviderName";}
            if($wallet == "five"){$name="walletFiveApi"; $name2="walletFiveProvider"; $name3="walletFiveProviderName";}
            if($wallet == "six"){$name="walletSixApi"; $name2="walletSixProvider"; $name3="walletSixProviderName";}

            $apiKey=self::getConfigValue($details,$name);
            $hostuserurl=self::getConfigValue($details,$name2);
            $apiProvider=self::getConfigValue($details,$name3);

            if(empty($apiKey) || empty($hostuserurl)){

                $response["status"] = "fail";
                $response["balance"] = "0";
                $response["provider"] = "Not Set";

                return $response;
            }

            $aunType = "Basic"; $accMethod=0;
            
            if(strpos($hostuserurl, 'n3tdata247') !== false){$aunType = "Basic"; $accMethod=1;}
            elseif (strpos($hostuserurl, 'bilalsadasub') !== false){$aunType = "Basic";}
            elseif (strpos($hostuserurl, 'rabdata360') !== false){$aunType = "Basic"; $accMethod=1;}
            elseif (strpos($hostuserurl, 'legitdataway') !== false){$aunType = "Basic"; $accMethod=1;}
            elseif (strpos($hostuserurl, 'n3tdata') !== false){$aunType = "Basic"; }
            elseif (strpos($hostuserurl, 'azaravtu') !== false){$aunType = "Bearer"; $accMethod=1;}
            else{$aunType = "Token";}

            // ------------------------------------------
            //  Get User Access Token
            // ------------------------------------------
            
             $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $hostuserurl);
                curl_setopt($ch, CURLOPT_POST, $accMethod);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                   $ch, CURLOPT_HTTPHEADER, [
                        "Authorization: $aunType $apiKey",
                    ]
                );
            $json = curl_exec($ch);
            $result=json_decode($json);
            curl_close($ch);
           
            if(isset($result->user->wallet_balance)){
                $response["status"] = "success";
                $response["balance"] = $result->user->wallet_balance;
            }
            elseif(isset($result->user->wallet)){
                $response["status"] = "success";
                $response["balance"] = number_format($result->user->wallet,2);
            }
            
            elseif(isset($result->wallet_balance)){
                $response["status"] = "success";
                $response["balance"] = $result->wallet_balance;
            }
            elseif(isset($result->balance)){
                $response["status"] = "success";
                $response["balance"] = $result->balance;
            }
            else{
                $response["status"] = "fail";
                $response["balance"] = "0";
            }


            $response["provider"] = $apiProvider;
            
            if ($response["status"] === "fail") {
                // Retry with another request
                $ch = curl_init();
                curl_setopt_array($ch, array(
                    CURLOPT_URL => $hostuserurl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: $aunType $apiKey"
                    ),
                ));
                $json = curl_exec($ch);
                $result = json_decode($json);
                curl_close($ch);

                if (isset($result->user->wallet_balance)) {
                    $response["status"] = "success";
                    $response["balance"] = $result->user->wallet_balance;
                } elseif (isset($result->wallet_balance)) {
                    $response["status"] = "success";
                    $response["balance"] = $result->wallet_balance;
                } elseif (isset($result->balance)) {
                    $response["status"] = "success";
                    $response["balance"] = $result->balance;
                } else {
                    $response["status"] = "fail";
                    $response["balance"] = "0";
                }

                $response["provider"] = $apiProvider;
            }

            return $response;
        }
        
    }

?>