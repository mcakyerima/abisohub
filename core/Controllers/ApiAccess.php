<?php

    class ApiAccess extends Controller{

        public $model;

		public function __construct(){
			$this->model=new ApiModel; 
		}

        //Send Email Notification
        public function sendEmailNotification($subject,$message,$email){
            $this->model->sendEmailNotification($subject,$message,$email);
        }

        //Verify Access Token
		public function validateAccessToken($token){                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
			$result=$this->model->validateAccessToken($token);
            $response = array();
			if(is_object($result)){
                $response["status"] = "success";
                $response["usertype"] = $result->sType;
                $response["userid"] = $result->sId;
                $response["name"] = $result->sLname ." ". $result->sFname;
                $response["balance"] = $result->sWallet;
                $response["phone"] = $result->sPhone;
                $response["refearedby"] = $result->sReferal;
                $response["regstatus"] = $result->sRegStatus;
            }
		    else{$response["status"] = "fail";} 
            return $response;
		}

        //Fetch User Details
        public function getUserDetails($token){
			$result=$this->model->getUserDetails($token);
            $response = array();
			$response["name"] = $result->sLname ." ". $result->sFname;
            $response["balance"] = number_format($result->sWallet,2);
            return $response;
		}

        //Fetch Site Settings
        public function getSiteSettings(){
			$result=$this->model->getSiteSettings();
            return $result;
		}

        //Verify Network Id
        public function verifyNetworkId($network){
            $result = $this->model->verifyNetworkId($network);
            $response = array();
            if(is_object($result)){
                $response = (array) $result;
                $response["status"] = "success";
             }
            else{$response["status"] = "fail";}
            return $response;
        }

        
        //Verify Data Plan Id
        public function verifyDataPlanId($network,$data_plan,$usertype){
            $result = $this->model->verifyDataPlanId($network,$data_plan);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["dataplan"] = $result->planid;
                $response["name"] = $result->name;
                if($usertype == 3){$response["amount"] = (float) $result->vendorprice;}
                elseif($usertype == 2){$response["amount"] = (float) $result->agentprice;}
                else{$response["amount"] = (float) $result->userprice;}
                $response["buyprice"] = $result->price;
                $response["datatype"] = $result->type;
                $response["day"] = $result->day;
             }
            else{$response["status"] = "fail";}

            return $response;
        }

        //Verify Data Plan Id
        public function verifyDataPinId($network,$data_plan,$usertype){
            $result = $this->model->verifyDataPinId($network,$data_plan);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["datapin"] = $result->planid;
                $response["name"] = $result->name;
                if($usertype == 3){$response["amount"] = (float) $result->vendorprice;}
                elseif($usertype == 2){$response["amount"] = (float) $result->agentprice;}
                else{$response["amount"] = (float) $result->userprice;}
                $response["buyprice"] = $result->price;
                $response["datatype"] = $result->type;
                $response["day"] = $result->day;
             }
            else{$response["status"] = "fail";}

            return $response;
        }
         
        //Verify Recharge Card Plan Id
        public function verifyRechargeCardPinId($network,$plan,$usertype){
            $result = $this->model->verifyRechargeCardPinId($network,$plan);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["actualplan"] = $result->planid;
                $response["plansize"] = $result->planSize;
                if($usertype == 3){$response["amount"] = (float) $result->aVendorPrice;}
                elseif($usertype == 2){$response["amount"] = (float) $result->aAgentPrice;}
                else{$response["amount"] = (float) $result->aUserPrice;}
                $response["buyprice"] = $result->aBuyPrice;
                $response["loadpin"] = $result->loadpin;
                $response["checkbalance"] = $result->checkbalance;
             }
            else{$response["status"] = "fail";}

            return $response;
        }

        //Verify Electricity Provider Id
        public function verifyElectricityId($provider){
            $result = $this->model->verifyElectricityId($provider);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["electricityid"] = $result->electricityid;
                $response["provider"] = $result->provider;
                $response["providerStatus"] = $result->providerStatus;
             }
            else{$response["status"] = "fail";}

            return $response;
        }

        
        //Verify Exam Provider Id
        public function verifyExamId($provider){
            $result = $this->model->verifyExamId($provider);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["examid"] = $result->examid;
                $response["provider"] = $result->provider;
                $response["providerStatus"] = $result->providerStatus;
                $response["amount"] = $result->price;
                $response["buying_price"] = $result->buying_price;
             }
            else{$response["status"] = "fail";}

            return $response;
        }
         

        //Verify Cable Provider Id
        public function verifyCableId($provider){
            $result = $this->model->verifyCableId($provider);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["cableid"] = $result->cableid;
                $response["provider"] = $result->provider;
                $response["providerStatus"] = $result->providerStatus;
             }
            else{$response["status"] = "fail";}

            return $response;
        }

        //Verify Cable Plan Id
        public function verifyCablePlanId($provider,$plan,$usertype){
            $result = $this->model->verifyCablePlanId($provider,$plan);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["cableplan"] = $result->planid;
                $response["name"] = $result->name;
                if($usertype == 3){$response["amount"] = (float) $result->vendorprice;}
                elseif($usertype == 2){$response["amount"] = (float) $result->agentprice;}
                else{$response["amount"] = (float) $result->userprice;}
                $response["day"] = $result->day;
                $response["buyprice"] = $result->price;
             }
            else{$response["status"] = "fail";}

            return $response;
        }

        //Verify Phone Number
        public function verifyPhoneNumber($phone,$network_name){
            $response = array();
            $validate = substr($phone, 0, 4);
            $response["status"] = "success";


            //Automatically Disable Validator
                return $response;
            //Remove The Above Line To Allow Validator

            if($network_name=="MTN"){
                if(strpos(" 0702 0703 0713 0704 0706 0716 0802 0803 0806 0810 0813 0814 0816 0903 0913 0906 0916 0804 ", $validate) == FALSE || strlen($phone) != 11){
                  $response['msg'] = "This number is not an $network_name Number $phone";
                  $response["status"] = "fail";
                }
            }
            else if($network_name=="GLO"){
                if(strpos(" 0805 0705 0905 0807 0907 0707 0817 0917 0717 0715 0815 0915 0811 0711 0911 ", $validate) == FALSE || strlen($phone) != 11){
                 $response['msg'] = "This number is not an $network_name Number $phone";
                 $response["status"] = "fail";
                }
            }
            else if($network_name=="AIRTEL"){
                if(strpos(" 0904 0802 0902 0702 0808 0908 0708 0918 0818 0718 0812 0912 0712 0801 0701 0901 0907 0917 ", $validate) == FALSE || strlen($phone) != 11){
                    $response['msg'] = "This number is not an $network_name Number $phone";
                    $response["status"] = "fail";
                }
            }
            else if($network_name =="9MOBILE"){
                if(strpos(" 0809 0909 0709 0819 0919 0719 0817 0917 0717 0718 0918 0818 0808 0708 0908 ", $validate) == FALSE || strlen($phone) != 11){
                    $response['msg'] = "This number is not an $network_name Number $phone";
                    $response["status"] = "fail";
                }
            }
            else{
                $response['msg'] = "Unidentified Network Id";
                $response["status"] = "fail";
            }

            return $response;
        }

        //Calculate Airtime Discount
        public function calculateAirtimeDiscount($network,$airtime_type,$amount,$usertype){
            $response = array();
            $usertype = (float) $usertype;
            $network = (float) $network;
            $amount = (float) $amount;

            //Get Disount Persentage And Calculate Discount
            $result=$this->model->calculateAirtimeDiscount($network,$airtime_type);
            if($usertype == 3){$per = (float) $result->aVendorDiscount;}
            elseif($usertype == 2){$per = (float) $result->aAgentDiscount;}
            else{$per = (float) $result->aUserDiscount;}
            $amounttopay=($amount * $per)/100;
            $buyper = (float) $result->aBuyDiscount;
            $buyamount = ($amount * $buyper)/100;

            $response["status"] = "success"; 
            $response["discount"] = $amounttopay;
            $response["buyamount"] = $buyamount;
            
            return $response;
        }

        //Check If Transaction Exist
		public function checkIfTransactionExist($ref){
			$result=$this->model->checkIfTransactionExist($ref);
            $response = array();
			if($result == 0){$response["status"] = "fail";}
		    else{$response["status"] = "success";} 
            return $response;
		}

        //Check For Transaction Duplicate
		public function checkTransactionDuplicate($servicename,$servicedesc){
			$result=$this->model->checkTransactionDuplicate($servicename,$servicedesc);
            $response = array();
			if(is_object($result)){
			    date_default_timezone_set('Africa/Lagos');
			    $dateNow=date("Y-m-d H:i:s");
                $transDate = new DateTime($result->date);
                $transDateNow = new DateTime($dateNow);
                $timeLength = (float) $transDateNow->getTimestamp() - $transDate->getTimestamp();
                //file_put_contents("responsetime.txt","Seconds: ".$timeLength.", Trans Date: ".$result->date.", Date Now: ".$dateNow);
                
                //If same transaction occured in the last 30 secs, then dont send transaction.
                if($timeLength > 30){$response["status"] = "success";}
			    else{$response["status"] = "fail";}
			}
		    else{$response["status"] = "success";} 
            return $response;
		}

       
        //Debit User BeforeTransaction
        public function debitUserBeforeTransaction($userid,$deibt){
            return $this->model->debitUserBeforeTransaction($userid,$deibt);
        }
 
        //Record Transaction & Debit User
        public function recordTransaction($userid,$servicename,$servicedesc,$amountopay,$userbalance,$ref,$status){
            $response = array();
            $oldbalance = (float) $userbalance;
            $newbalance = $oldbalance - $amountopay;

            $result=$this->model->recordTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status);
            $response["status"] = "success";
            $response["amount"] = $amountopay;
            $response["oldbal"] = $oldbalance;
            $response["newbal"] = $newbalance;
            $response["service"] = $servicename;
            $response["description"] = $servicedesc;
            return $response;
        }

        //Save Profit
        public function saveProfit($ref,$profit){
            $result=$this->model->saveProfit($ref,$profit);
        }

        

        //Save Data Pin
        public function saveDataPin($userid,$ref,$business,$networkname,$dataplansize,$quantity,$serial,$pin,$load_pin,$check_balance){
            $result=$this->model->saveDataPin($userid,$ref,$business,$networkname,$dataplansize,$quantity,$serial,$pin,$load_pin,$check_balance);
        }
        
        //Save Recharge Pin
        public function saveRechargePin($userid,$ref,$business,$networkname,$plansize,$quantity,$serial,$pin,$load_pin,$check_balance){
            $result=$this->model->saveRechargePin($userid,$ref,$business,$networkname,$plansize,$quantity,$serial,$pin,$load_pin,$check_balance);
        }


        //Update Transaction Status
        public function updateTransactionStatus($userid,$ref,$amountopay,$status){
            $response = array();
            $result=$this->model->updateTransactionStatus($userid,$ref,$amountopay,$status);
        }

         //----------------------------------------------------------------------------------------------------------------
		// Referal Bonus
		//----------------------------------------------------------------------------------------------------------------
		 
		public function creditReferalBonus($referal,$referalname,$refearedby,$service){
            $result=$this->model->creditReferalBonus($referal,$referalname,$refearedby,$service);
        }

        //Record Transaction & Debit User
        public function recordMonnifyTransaction($userid,$servicename,$servicedesc,$amount,$userbalance,$ref,$status){
            $response = array();
            $oldbalance = (float) $userbalance;
            $newbalance = $oldbalance + $amount;

            $result=$this->model->recordMonnifyTransaction($userid,$servicename,$servicedesc,$ref,$amount,$oldbalance,$newbalance,$status);
            $response["status"] = "success";
            $response["amount"] = $amount;
            $response["oldbal"] = $oldbalance;
            $response["newbal"] = $newbalance;
            $response["service"] = $servicename;
            $response["description"] = $servicedesc;
            return $response;
        }

        //Record Transaction & Debit User
        public function recordPaystackTransaction($userid,$servicename,$servicedesc,$amount,$userbalance,$ref,$status){
            $response = array();
            $oldbalance = (float) $userbalance;
            $newbalance = $oldbalance + $amount;

            $result=$this->model->recordPaystackTransaction($userid,$servicename,$servicedesc,$ref,$amount,$oldbalance,$newbalance,$status);
            $response["status"] = "success";
            $response["amount"] = $amount;
            $response["oldbal"] = $oldbalance;
            $response["newbal"] = $newbalance;
            $response["service"] = $servicename;
            $response["description"] = $servicedesc;
            return $response;
        }

        //Verify Monnify Transaction
		public function verifyMonnifyRef($email,$monnifyhash,$token){
			$result=$this->model->verifyMonnifyRef($email,$monnifyhash,$token);
            $response = array();
			if(is_object($result)){
                $response["status"] = "success";
                $response["userid"] = $result->sId;
                $response["name"] = $result->sLname ." ". $result->sFname;
                $response["balance"] = $result->sWallet;
                $response["charges"] = $result->charges;
            }
		    else{$response["status"] = "fail";} 
            return $response;
		}

        //Verify Paystack Transaction
		public function verifyPaystackRef($email,$token){
			$result=$this->model->verifyPaystackRef($email,$token);
            $response = array();
			if(is_object($result)){
                $response["status"] = "success";
                $response["userid"] = $result->sId;
                $response["balance"] = $result->sWallet;
                $response["amount"] = $result->amount;
                $response["charges"] = $result->charges;
            }
		    else{
                $response["status"] = "fail";
                $response["msg"] = $result;
            } 
            return $response;
		}
		
		// ----------------------------------------------------------------------
		//Alpha Topup
		// ----------------------------------------------------------------------
		
		//Get Alpha Topup
		public function getAlphaTopupPlans(){
			$data=$this->model->getAlphaTopupPlans();
			return $data;
		}
		
		//Calculate AlphaTopup Discount
        public function calculateAlphaTopupDiscountDiscount($amount,$usertype){
            $response = array();
            $usertype = (float) $usertype;
            $amount = (float) $amount;

            //Get Disount Persentage And Calculate Discount
            $result=$this->model->calculateAlphaTopupDiscountDiscount($amount);
            if($usertype == 3){$amounttopay = (float) $result->vendor;}
            elseif($usertype == 2){$amounttopay = (float) $result->agent;}
            else{$amounttopay = (float) $result->sellingPrice;}
            
            $buyamount = (float) $result->buyingPrice;
            

            $response["status"] = "success"; 
            $response["discount"] = $amounttopay;
            $response["buyamount"] = $buyamount;
            
            return $response;
        }
        
        //Record Alpha Transaction Transaction & Debit User
        public function sendAlphaNotification($amount,$servicedesc){
            return $result=$this->model->sendAlphaNotification($amount,$servicedesc);
        }

        //Update Transaction Status
        public function updateTransactionWithRealResponse($ref,$response){
            $result=$this->model->updateTransactionWithRealResponse($ref,$response);
        }

        //Verify Kuda Notification
		public function verifyKudaNotification($email,$key,$useraccount){
			return $result=$this->model->verifyKudaNotification($email,$key,$useraccount);
		}
		
		//Record Transaction & Debit User
        public function recordKudaTransaction($userid,$servicename,$servicedesc,$amount,$userbalance,$ref,$status){
            $response = array();
            $oldbalance = (float) $userbalance;
            $newbalance = $oldbalance + $amount;

            $result=$this->model->recordKudaTransaction($userid,$servicename,$servicedesc,$ref,$amount,$oldbalance,$newbalance,$status);
            $response["status"] = "success";
            $response["amount"] = $amount;
            $response["oldbal"] = $oldbalance;
            $response["newbal"] = $newbalance;
            $response["service"] = $servicename;
            $response["description"] = $servicedesc;
            return $response;
        }
        
        //Verify Kuda Notification
		public function completeKudaFundingByWithdrawal($amountkobo,$email){
			$obj = new Account;
			$result=$obj->completeKudaFundingByWithdrawal($amountkobo,$email);
			return $result;
		}
		
		//Get Some RechargeCard Pins
		public function getSomeRechargeCardPins($network,$quantity,$amount,$user){
			$result=$this->model->getSomeRechargeCardPins($network,$quantity,$amount,$user);
			return $result;
		}

        //Get Number Of Available Recharge Pins
        public function getNumberOfAvailablePins(){
            $data=$this->model->getNumberOfAvailablePins();
            return $data;
        }

          //----------------------------------------------------------------------
        // Verify Smile Data Plan Id
        //----------------------------------------------------------------------
        
        //Verify Smile Data Plan Id
        public function verifySmileDataPlanId($BundleTypeCode,$usertype){
            $result = $this->model->verifySmileDataPlanId($BundleTypeCode);
            $response = array();
            if(is_object($result)){
                $response["status"] = "success";
                $response["BundleTypeCode"] = $result->BundleTypeCode;
                $response["description"] = $result->description;
                if($usertype == 3){$response["amount"] = (int) $result->price;}
                elseif($usertype == 2){$response["amount"] = (int) $result->price;}
                else{$response["amount"] = (int) $result->price;}
              
                $response["validity"] = $result->validity;
                $dd=$this->getSiteSettings();
                $response["smilediscount"]=$dd->smilediscount;
             }
            else{$response["status"] = "fail";}

            return $response;
        }
        
        public function creditSmileBonus($userId,$amount,$service,$servicsdesc){
            $result=$this->model->creditSmileBonus($userId,$amount,$service,$servicsdesc);
        }

        //Verify Payversel Notification
		public function verifyPayvesselRef($email,$token,$payload){
			return $result=$this->model->verifyPayvesselRef($email,$token,$payload);
		}

        //Record Transaction & Debit User
        public function recordPayvesselTransaction($userid,$servicename,$servicedesc,$amount,$userbalance,$ref,$status){
            $response = array();
            $oldbalance = (float) $userbalance;
            $newbalance = $oldbalance + $amount;

            $result=$this->model->recordPayvesselTransaction($userid,$servicename,$servicedesc,$ref,$amount,$oldbalance,$newbalance,$status);
            $response["status"] = "success";
            $response["amount"] = $amount;
            $response["oldbal"] = $oldbalance;
            $response["newbal"] = $newbalance;
            $response["service"] = $servicename;
            $response["description"] = $servicedesc;
            return $response;
        } 

        //Update Transaction With Server Log
        public function updateTransactionWithApiResponseLog($ref,$response){
            $result=$this->model->updateTransactionWithApiResponseLog($ref,$response);
        }

        //Debit My User Before Transaction
        public function debitMyUserBeforeTransaction($userid,$deibt,$servicename,$servicedesc,$ref,$status){
            return $this->model->debitMyUserBeforeTransaction($userid,$deibt,$servicename,$servicedesc,$ref,$status);
        }

        //Update Transaction Status
        public function updateMyTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog){
            $response = array();
            $result=$this->model->updateMyTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog);
        }

        //Update Electric Transaction Status
        public function updateMyElectricTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog){
            $response = array();
            $result=$this->model->updateMyElectricTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog);
        }

        //Delete Transaction Due To Insufficient Fund Detected On Multiple Request Sent
        public function deleteTransactionDueToInsufficientBalance($ref){
            $response = array();
            $result=$this->model->deleteTransactionDueToInsufficientBalance($ref);
        }

 

    }

?>