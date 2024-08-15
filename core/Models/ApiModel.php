<?php

	class ApiModel extends Model{ 

		//----------------------------------------------------------------------------------------------------------------
		// API Access Management
		//----------------------------------------------------------------------------------------------------------------

        //Send Email Notification
        public function sendEmailNotification($subject,$message,$email){
            $subject.= "(".$this->sitename.")";
			self::sendMail($email,$subject,$message);
        }
        
		//Validate API Token
		public function validateAccessToken($token){
			$dbh=$this->connect();
			$sql = "SELECT * FROM subscribers WHERE sApiKey=:token AND sRegStatus='0'";
            $query = $dbh->prepare($sql);
            $query->bindParam(':token',$token,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $result;} else{return 1;}
		}

        //Get User Details
		public function getUserDetails($token){
			$dbh=$this->connect();
			$sql = "SELECT * FROM subscribers WHERE sApiKey=:token AND sRegStatus='0'";
            $query = $dbh->prepare($sql);
            $query->bindParam(':token',$token,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            return $result;
		}
 
        /**
		 * Fetch transactions by user ID with a limit.
		 *
		 * This function retrieves transactions for a specified user ID, with an optional limit on the number of transactions returned.
		 * It returns the transactions if found, or 0 if no transactions are found.
		 *
		 * @param string $userId The ID of the user whose transactions are to be fetched.
		 * @param int $limit The maximum number of transactions to fetch (default is 10).
		 * @return array|int An array of transactions if found, or 0 if no transactions are found.
		 */
		public function fetchTransactionsByUserId($userId, $offset) {
			$dbh = $this->connect();
			$stmt = 'SELECT * FROM transactions WHERE sId = :userId LIMIT :offset';
			$query = $dbh->prepare($stmt);
			$query->bindParam(':userId', $userId, PDO::PARAM_INT);
			$query->bindParam(':offset', $offset, PDO::PARAM_INT);
			$query->execute();

			// Check if there are any transactions and return the result
			$transactions = $query->fetchAll(PDO::FETCH_ASSOC);

            // return $transactions;

            if (count($transactions) > 0) {
                return $transactions;
            } else {
                return "No transactions found for user.";
            }
		}
        

        /**
         * Fetches all Data plans in Database
         */
        public function getAllDataPlans()
        {
            $dbh = $this->connect();
            $sql = "SELECT * FROM dataplans";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result=$query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * Fetches all Networks in the Database
         */
        public function getAllNetworks($id = null)
        {
            $dbh = $this->connect();
            $sql = "SELECT * FROM networkId";
            if (isset($id)) {
                $sql .= " WHERE nId = '$id'";
            }
            $query = $dbh->prepare($sql);
            $query->execute();
            $result=$query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }




        //Verify Network Id
		public function verifyNetworkId($network){
			$dbh=$this->connect();
            $network = (int) $network;
			$sql = "SELECT * FROM  networkid WHERE nId=:network";
            $query = $dbh->prepare($sql);
            $query->bindParam(':network',$network,PDO::PARAM_INT);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}

        //Verify Data Plan Id
		public function verifyDataPlanId($network,$data_plan){
			$dbh=$this->connect();
			$sql = "SELECT * FROM dataplans WHERE datanetwork=:network AND pId=:plan ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':network',$network,PDO::PARAM_STR);
            $query->bindParam(':plan',$data_plan,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}

         //Verify Data Plan Id
		public function verifyDataPinId($network,$data_plan){
			$dbh=$this->connect();
			$sql = "SELECT * FROM datapins WHERE datanetwork=:network AND dpId=:plan ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':network',$network,PDO::PARAM_STR);
            $query->bindParam(':plan',$data_plan,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}
		
		//Get Some Recharge Card Pins
		public function getSomeRechargeCardPins($network,$quantity,$amount,$user){
            $network=(int) $network;
			$dbh=$this->connect();
            
            $sql = "
                SELECT * 
                FROM airtimepinstock WHERE network=$network
                AND status='Unused' 
                AND amount=:amount 
                ORDER BY RAND() LIMIT $quantity
            ";

			/*
            
            Alternative Option For Optimization On Large Table But Adding Network Id Gives Issues
            $sql = "
    			SELECT *
                  FROM airtimepinstock AS r1 JOIN
                       (SELECT CEIL(RAND() *
                                     (SELECT MAX(tId)
                                        FROM airtimepinstock)) AS id)
                        AS r2
                 WHERE r1.tId >= r2.id AND r1.status='Unused' AND r1.amount=:amount
                 ORDER BY r1.tId ASC
                 LIMIT $quantity;
                 
            ";*/ 
            
            $query = $dbh->prepare($sql);
            $query->bindParam(':amount',$amount,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                
                //Check If Retrived Pins Match Required Quantity
                if($query->rowCount() <> $quantity){return 2;}
                
                $result=$query->fetchAll(PDO::FETCH_ASSOC);
                
                //After Fetching Pins, Update The Pin Status So Others And Use It
                $updateSql=""; $updateSqlForm="";
                
                foreach ($result as $items) {$updateSqlForm.="({$items["tId"]},'Used','{$user}'),"; }
                
                $updateSqlForm = rtrim($updateSqlForm,",");
                
                $updateSql = "INSERT INTO `airtimepinstock` (tId,status,soldto) VALUES $updateSqlForm ON DUPLICATE KEY UPDATE status=VALUES(status),soldto=VALUES(soldto)";
                
                $queryUpdate = $dbh->prepare($updateSql);
                if($queryUpdate->execute()){ return $result;} else{return 1;}
                
            } 
            else{return 2;}
            
		}
		
		//Verify Recharge Plan Id
		public function verifyRechargeCardPinId($network,$plan){
			$dbh=$this->connect();
			$sql = "SELECT * FROM airtimepin WHERE aNetwork=:network AND aId=:plan ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':network',$network,PDO::PARAM_STR);
            $query->bindParam(':plan',$plan,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}


        //Verify Electricity Id
		public function verifyElectricityId($provider){
			$dbh=$this->connect();
			$sql = "SELECT * FROM electricityid WHERE eId=:providers ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':providers',$provider,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
             
		}

        //Verify Exam Id
		public function verifyExamId($provider){
			$dbh=$this->connect();
			$sql = "SELECT * FROM examid WHERE eId=:providers ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':providers',$provider,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}

        //Verify Cable Id
		public function verifyCableId($provider){
			$dbh=$this->connect();
			$sql = "SELECT * FROM cableid WHERE cId=:providers ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':providers',$provider,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}

        //Verify Cable Plan Id
		public function verifyCablePlanId($provider,$plan){
			$dbh=$this->connect();
			$sql = "SELECT * FROM cableplans WHERE cableprovider=:provider AND cpId=:plan ";
            $query = $dbh->prepare($sql);
            $query->bindParam(':provider',$provider,PDO::PARAM_STR);
            $query->bindParam(':plan',$plan,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
             
		}

        //Calculate Airtime Discount
		public function calculateAirtimeDiscount($network,$airtime_type){
			$dbh=$this->connect();
			$sql = "SELECT * FROM airtime WHERE aNetwork=:n AND aType=:ty";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_INT);
            $query->bindParam(':ty',$airtime_type,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            return $result;
		}

        //Check If Transaction Exist
		public function checkIfTransactionExist($ref){
			$dbh=$this->connect();
			$sql = "SELECT * FROM transactions WHERE transref=:ref";
            $query = $dbh->prepare($sql);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return 0;} else{return 1;}
		}

        //Check For Transaction Duplicate
		public function checkTransactionDuplicate($servicename,$servicedesc){
			$dbh=$this->connect();
			$sql = "SELECT date FROM transactions WHERE servicename=:sn AND servicedesc=:sd AND status=0 ORDER BY tId DESC LIMIT 1";
            $query = $dbh->prepare($sql);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $result;} else{return 1;}
		}

        //Get API Details
		public function getApiDetails(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM apiconfigs";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result=$query->fetchAll(PDO::FETCH_OBJ);
            return $result;
        }

        //Get Site Settings Details
		public function getSiteSettings(){
			$dbh=$this->connect();
            $sqlA = "SELECT * FROM sitesettings WHERE sId=1";
            $queryA = $dbh->prepare($sqlA); $queryA->execute();
            $siteData=$queryA->fetch(PDO::FETCH_OBJ);
            return $siteData;
		}
 
        //Debit User BeforeTransaction
        public function debitUserBeforeTransaction($userid,$deibt){
            $dbh=$this->connect();
			$userid=(float) $userid;
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$deibt,PDO::PARAM_STR);
            if($queryD->execute()){return "success";}else{return "fail";}
        }

        //Save Profit
        public function saveProfit($ref,$profit){
            $dbh=$this->connect();
			$sqlD = "UPDATE transactions SET profit=:p WHERE transref=:ref";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':p',$profit,PDO::PARAM_STR);
            $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
            if($queryD->execute()){return "success";}else{return "fail";}
        }

        //Save Data Pin
        public function saveDataPin($userid,$ref,$business,$networkname,$dataplansize,$quantity,$serial,$pin,$load_pin,$check_balance){
            $dbh=$this->connect();
			$sql = "INSERT INTO datatokens SET sId=:user,tRef=:ref,business=:b,network=:net,datasize=:size,quantity=:q,serial=:s,tokens=:t,loadpin=:loadpin,checkbalance=:checkbalance";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':q',$quantity,PDO::PARAM_STR);
            $query->bindParam(':s',$serial,PDO::PARAM_STR);
            $query->bindParam(':t',$pin,PDO::PARAM_STR);
            $query->bindParam(':b',$business,PDO::PARAM_STR);
            $query->bindParam(':net',$networkname,PDO::PARAM_STR);
            $query->bindParam(':size',$dataplansize,PDO::PARAM_STR);
            $query->bindParam(':loadpin',$load_pin,PDO::PARAM_STR);
            $query->bindParam(':checkbalance',$check_balance,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
        }
        
        //Save Recharge Pin
        public function saveRechargePin($userid,$ref,$business,$networkname,$plansize,$quantity,$serial,$pin,$load_pin,$check_balance){
            $dbh=$this->connect();
			$sql = "INSERT INTO airtimetokens SET sId=:user,tRef=:ref,business=:b,network=:net,amount=:size,quantity=:q,serial=:s,tokens=:t,loadpin=:loadpin,checkbalance=:checkbalance";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':q',$quantity,PDO::PARAM_STR);
            $query->bindParam(':s',$serial,PDO::PARAM_STR);
            $query->bindParam(':t',$pin,PDO::PARAM_STR);
            $query->bindParam(':b',$business,PDO::PARAM_STR);
            $query->bindParam(':net',$networkname,PDO::PARAM_STR);
            $query->bindParam(':size',$plansize,PDO::PARAM_STR);
            $query->bindParam(':loadpin',$load_pin,PDO::PARAM_STR);
            $query->bindParam(':checkbalance',$check_balance,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
        }



        //Record Monnify Transaction 
		public function recordMonnifyTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status){
			$dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
            $date=date("Y-m-d H:i:s");
            
            //Check If Discount Already Exist
			$queryC=$dbh->prepare("SELECT transref FROM transactions WHERE transref=:transref");
			$queryC->bindParam(':transref',$ref,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 1;}
			
            //If transaction was successful, debit user wallet
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $queryD->execute();

            //Record Transaction
			$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->bindParam(':a',$amountopay,PDO::PARAM_STR);
            $query->bindParam(':s',$status,PDO::PARAM_INT);
            $query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
            $query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $query->bindParam(':d',$date,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
		}

        //Record Paystack Transaction 
		public function recordPaystackTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status){
			$dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
            $date=date("Y-m-d H:i:s");
            
            //Check If Ref Already Exist
			$queryC=$dbh->prepare("SELECT transref FROM transactions WHERE transref=:transref");
			$queryC->bindParam(':transref',$ref,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 1;}
			
            //If transaction was successful, debit user wallet
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $queryD->execute();

            //Record Transaction
			$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->bindParam(':a',$amountopay,PDO::PARAM_STR);
            $query->bindParam(':s',$status,PDO::PARAM_INT);
            $query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
            $query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $query->bindParam(':d',$date,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
		}
		
		

        //Record Transaction ANd Debit User
		public function recordTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status){
			$dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
            $date=date("Y-m-d H:i:s");

            //If transaction was successful, debit user wallet
            if($status == 1){ $newbalance=$oldbalance; }
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $queryD->execute();

            //Record Transaction
			$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->bindParam(':a',$amountopay,PDO::PARAM_STR);
            $query->bindParam(':s',$status,PDO::PARAM_INT);
            $query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
            $query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $query->bindParam(':d',$date,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
		}

        //Update Transaction Status
        public function updateTransactionStatus($userid,$ref,$amountopay,$status){
            $dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
			$amountopay=(float) $amountopay;

            //If Transaction Failed, Refund User Since He Ha Been Debited Already
            if($status == 1){
                $sqlW = "SELECT sWallet FROM subscribers WHERE sId=$userid";
                $queryW = $dbh->prepare($sqlW);
                $queryW->execute();
                $resultW=$queryW->fetch(PDO::FETCH_OBJ);
                $oldbalance = (float) $resultW->sWallet;
                $newbalance = $oldbalance + $amountopay;

                $sqlS = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
                $queryS = $dbh->prepare($sqlS);
                $queryS->bindParam(':id',$userid,PDO::PARAM_INT);
                $queryS->bindParam(':nb',$newbalance,PDO::PARAM_STR);
                $queryS->execute();

                //Update Transaction Status
                $sqlD = "UPDATE transactions SET status=:status,newbal=:nb WHERE sId=:user AND transref=:ref";
                $queryD = $dbh->prepare($sqlD);
                $queryD->bindParam(':user',$userid,PDO::PARAM_INT);
                $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
                $queryD->bindParam(':status',$status,PDO::PARAM_INT);
                $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryD->execute();
            }
            else{
                //Update Transaction Status
                $sqlD = "UPDATE transactions SET status=:status WHERE sId=:user AND transref=:ref";
                $queryD = $dbh->prepare($sqlD);
                $queryD->bindParam(':user',$userid,PDO::PARAM_INT);
                $queryD->bindParam(':status',$status,PDO::PARAM_INT);
                $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryD->execute();
            }
        }

        //----------------------------------------------------------------------------------------------------------------
		// Referal Bonus
		//----------------------------------------------------------------------------------------------------------------
		 
		public function creditReferalBonus($referal,$referalname,$refearedby,$service){
            $dbh=$this->connect();

            //Get Site Details
            $sqlA = "SELECT * FROM sitesettings WHERE sId=1";
            $queryA = $dbh->prepare($sqlA); $queryA->execute();
            $siteData=$queryA->fetch(PDO::FETCH_OBJ);

            //Determine Referal Bonus
            if($service == "Airtime"){$refbonus = (float) $siteData->referalairtimebonus;}
            elseif($service == "Data"){$refbonus = (float) $siteData->referaldatabonus;}
            elseif($service == "Cable TV"){$refbonus = (float) $siteData->referalcablebonus;}
            elseif($service == "Exam Pin"){$refbonus = (float) $siteData->referalexambonus;}
            elseif($service == "Electricity Bill"){$refbonus = (float) $siteData->referalmeterbonus;}
            else{$refbonus = 0;}

            //If bonus is not activates or set to 0, terminate operation
            if($refbonus == 0){return 1;}

			$sql = "SELECT sId,sRefWallet FROM subscribers WHERE sPhone=:phone";
            $query = $dbh->prepare($sql);
			$query->bindParam(':phone',$refearedby,PDO::PARAM_STR);
			$query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
			
			if($query->rowCount() > 0){

				//Get User Balance
				$userId= $result->sId;
				$amount = (float) $refbonus;
            	$servicename = "Referral Bonus";
    			$servicedesc = "Referral Bonus Of N{$amount} For Referring {$referalname} ({$referal}). Bonus For {$service} Purchase.";
				$status = 0;
				$date=date("Y-m-d H:i:s");
				$ref = "REF_".rand(100,999)."_".time();

				//Record Transaction
                $sql2="
                    START TRANSACTION;
                        SELECT @oldwallet := sRefWallet FROM subscribers WHERE sId=:id LIMIT 1 FOR UPDATE;
                        SELECT @newwallet := @oldwallet + $amount;
                        UPDATE subscribers SET sRefWallet=@newwallet WHERE sId=:id;
                        INSERT INTO transactions SET sId=:id,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,
                        status=:s,oldbal=@oldwallet,newbal=@newwallet,date=:d;
                    COMMIT;
                ";
				$query2 = $dbh->prepare($sql2);
				$query2->bindParam(':id',$userId,PDO::PARAM_INT);
				$query2->bindParam(':ref',$ref,PDO::PARAM_STR);
				$query2->bindParam(':sn',$servicename,PDO::PARAM_STR);
				$query2->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
				$query2->bindParam(':a',$amount,PDO::PARAM_STR);
				$query2->bindParam(':s',$status,PDO::PARAM_INT);
				$query2->bindParam(':d',$date,PDO::PARAM_STR);
				$query2->execute();

				return 0;
			
			}
		}


        //Validate Monnify Transaction
		public function verifyMonnifyRef($email,$monnifyhash,$token){
			$dbh=$this->connect();
			
            //Get Api Key
            $sql = "SELECT value FROM apiconfigs WHERE name='monifySecrete'";
            $query = $dbh->prepare($sql);
            $query->execute(); 
            $result=$query->fetch(PDO::FETCH_OBJ);
            $monifySecrete=$result->value;
            $hash=$this->computeMonnifyHash($token, $monifySecrete);

            //Get Api Status
            $sql2 = "SELECT value FROM apiconfigs WHERE name='monifyCharges'";
            $query2 = $dbh->prepare($sql2);
            $query2->execute(); 
            $result2=$query2->fetch(PDO::FETCH_OBJ);
            $charges=$result2->value;
           
            if($hash == $monnifyhash){
                $sqlA = "SELECT * FROM subscribers WHERE sEmail=:e";
                $queryA = $dbh->prepare($sqlA);
                $queryA->bindParam(':e',$email,PDO::PARAM_STR);
                $queryA->execute();
                $resultA=$queryA->fetch(PDO::FETCH_OBJ);
                $resultA = (array) $resultA;
                $resultA["charges"] = $charges;
                $resultA = (object) $resultA;
                return $resultA;
            }
            else{return 1;}
		}
		
		//Compute Monnify Hash
		 public function computeMonnifyHash($stringifiedData, $clientSecret) {
          $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
          return $computedHash;
        }
        

        //Validate Paystack Transaction
		public function verifyPaystackRef($email,$reference){
			$dbh=$this->connect();

			//Get Api Key
            $sql = "SELECT value FROM apiconfigs WHERE name='paystackApi'";
            $query = $dbh->prepare($sql);
            $query->execute(); 
            $result=$query->fetch(PDO::FETCH_OBJ);
            $apiKey=$result->value;

            //Get Api Status
            $sql2 = "SELECT value FROM apiconfigs WHERE name='paystackCharges'";
            $query2 = $dbh->prepare($sql2);
            $query2->execute(); 
            $result2=$query2->fetch(PDO::FETCH_OBJ);
            $charges=$result2->value;
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        "accept: application/json",
                        "authorization: Bearer ".$apiKey,
                        "cache-control: no-cache"
                    ],
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            if($err){
                    return 'Curl Returned Error: ' . $err;
            }

            $tranx = json_decode($response);
            
            if(!$tranx->status){
                    // there was an error from the API
                    return 'API Returned Error: ' . $tranx->message;
            }

            if('success' == $tranx->data->status){
                $sqlA = "SELECT sId,sWallet FROM subscribers WHERE sEmail=:e";
                $queryA = $dbh->prepare($sqlA);
                $queryA->bindParam(':e',$email,PDO::PARAM_STR);
                $queryA->execute();
                $resultA=$queryA->fetch(PDO::FETCH_OBJ);
                $resultA = (array) $resultA;
                $resultA["amount"] = $tranx->data->amount;
                $resultA["charges"] = $charges;
                $resultA = (object) $resultA;
                return $resultA;
            }
            else{
                return "Transaction Not Verified";
                
            }
		}
		
		//----------------------------------------------------------------------------------------------------------------
		// Alpha Topup Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Alpha Topup Plans
		public function getAlphaTopupPlans(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM alphatopupprice";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Alpha Topup 
		public function sendAlphaNotification($amount,$servicedesc){
			$dbh=$this->connect();
            $contact = $this->getSiteSettings();
			$subject="Alpha Topup Request (".$this->sitename.")";
			$message="This is to notify you that there is a new request for Alpha Topup on your website ".$this->sitename.". Order Details : {$servicedesc}";
			$email=$contact->email;
			$check=self::sendMail($email,$subject,$message);
			return 0;
			
		}
		
		//Calculate Alpha Topup Discount
		public function calculateAlphaTopupDiscountDiscount($amount){
			$dbh=$this->connect();
			$sql = "SELECT * FROM alphatopupprice WHERE buyingPrice=:a";
            $query = $dbh->prepare($sql);
            $query->bindParam(':a',$amount,PDO::PARAM_INT);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            return $result;
		}

        //Update Transaction Status With Real Time Response
        public function updateTransactionWithRealResponse($ref,$response){
            //Update Transaction Status
            $dbh=$this->connect();
            $sqlD = "UPDATE transactions SET servicedesc=:servicedesc WHERE transref=:ref";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':servicedesc',$response,PDO::PARAM_STR);
            $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
            $queryD->execute();
        }


        //Validate Kuda Notification
		public function verifyKudaNotification($username,$key,$useraccount){
			$dbh=$this->connect();
			
            //Get Configuration Details
            $sql = "SELECT * FROM apiconfigs";
            $query = $dbh->prepare($sql);
            $query->execute(); 
            $result=$query->fetchAll(PDO::FETCH_OBJ);
            
            $kudaWebhookUser = $this->getConfigValue($result,"kudaWebhookUser");
            $kudaWebhookPass = $this->getConfigValue($result,"kudaWebhookPass");
            $kudaWebhookPass = base64_encode($kudaWebhookPass);
            $kudaCharges = (float) $this->getConfigValue($result,"kudaCharges");
            $kudaChargesType = $this->getConfigValue($result,"kudaChargesType");
            
            //Verify Notification Details
            if($kudaWebhookUser == $username && $kudaWebhookPass == $key):
            
                $sqlA = "SELECT * FROM subscribers WHERE sKudaBank=:e";
                $queryA = $dbh->prepare($sqlA);
                $queryA->bindParam(':e',$useraccount,PDO::PARAM_STR);
                $queryA->execute();
                $resultA=$queryA->fetch(PDO::FETCH_OBJ);
                if($queryA->rowCount() > 0):
                    $response = array();
                    $response["status"] = "success";
                    $response["userid"] = $resultA->sId;
                    $response["name"] = $resultA->sLname ." ". $resultA->sFname;
                    $response["balance"] = $resultA->sWallet;
                    $response["useremail"] = $resultA->sEmail;
                    $response["charges"] = $kudaCharges;
                    $response["chargestype"] = $kudaChargesType;
                    return (object) $response;
                endif;
            
            else:
                $response = array();
                $response["status"] = "fail";
                $response["message"] = "Not Found";
                return (object) $response;
            endif;
            
            
            
           
            
		}

        //Record KUDA Transaction 
		public function recordKudaTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status){
			$dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
            $date=date("Y-m-d H:i:s");
            
            //Check If Discount Already Exist
			$queryC=$dbh->prepare("SELECT transref FROM transactions WHERE transref=:transref");
			$queryC->bindParam(':transref',$ref,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 1;}
			
            //If transaction was successful, debit user wallet
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $queryD->execute();

            //Record Transaction
			$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->bindParam(':a',$amountopay,PDO::PARAM_STR);
            $query->bindParam(':s',$status,PDO::PARAM_INT);
            $query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
            $query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $query->bindParam(':d',$date,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
		}
		
        //Get Recharge Pin Discount
		public function getRechargePinDiscount(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM airtimepin a, networkid b WHERE a.aNetwork=b.networkid";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
        
		//Get Number Of Available Pins
		public function getNumberOfAvailablePins(){
			$dbh=$this->connect();
			$available = array();

            $details=$this->getApiConfiguration();
			$accessType = self::getConfigValue($details,"rechargePinMethod");
            if($accessType == "EXTERNAL"){return $this->getNumberOfAvailablePinsFromApi();}
			
			$rechargeCardPlans = $this->getRechargePinDiscount();
			foreach($rechargeCardPlans AS $plans){
			        
			        $network = $plans->networkid;
			        $amount = $plans->planSize;
			        
			    	$sql = "SELECT COUNT(tId) AS availablepins FROM airtimepinstock WHERE network=:n AND amount=:am AND status = 'Unused' ";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':n',$network,PDO::PARAM_STR);
                    $query->bindParam(':am',$amount,PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetch(PDO::FETCH_OBJ);
                    $availablepins = $results->availablepins;
                    
                    array_push($available,["network"=>$plans->network,"amount"=>$amount,"pins"=>$availablepins]);
                    
			}
			
			return $available;
		}

        public function getNumberOfAvailablePinsFromApi(){

			$response = array();
			$details=$this->getApiConfiguration();
			
			//Get Api Key Details
			$host = self::getConfigValue($details,"rechargePinProvider");
			
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $host."available/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			));
	
			$exereq = curl_exec($curl);
			$result=json_decode($exereq);
			curl_close($curl);
			
			if(isset($result->msg)){return $result->msg;} else{return null;}
	
		}

        
		//----------------------------------------------------------------------
        // Verify Smile Data Plan Id
        //----------------------------------------------------------------------
        
        //Verify Data Plan Id
		public function verifySmileDataPlanId($BundleTypeCode){
			$dbh=$this->connect();
			$sql = "SELECT * FROM smiledata WHERE BundleTypeCode=:BundleTypeCode";
            $query = $dbh->prepare($sql);
            $query->bindParam(':BundleTypeCode',$BundleTypeCode,PDO::PARAM_STR);
            $query->execute();
            if($query->rowCount() > 0){
                $result=$query->fetch(PDO::FETCH_OBJ);
                return $result;
            } 
            else{return 1;}
            
		}
		
		//----------------------------------------------------------------------------------------------------------------
		// Smile Bonus
		//----------------------------------------------------------------------------------------------------------------

        public function creditSmileBonus($userid,$amount,$service,$servicsdesc){
            $dbh=$this->connect();

            //Get Site Details
            $sqlA = "SELECT * FROM sitesettings WHERE sId=1";
            $queryA = $dbh->prepare($sqlA); $queryA->execute();
            $siteData=$queryA->fetch(PDO::FETCH_OBJ);
            
            
            //Calculate Bonus
            $per = (int) $siteData->smilediscount;
            $amount = (float) $amount;
            $bonus = ($amount * $per) / 100;
            
            //Get User Wallet
            $sql = "SELECT sId,sRefWallet FROM subscribers WHERE sId=:id";
            $query = $dbh->prepare($sql);
			$query->bindParam(':id',$userid,PDO::PARAM_INT);
			$query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
			
			if($query->rowCount() > 0){

				//Get User Balance
				$userId= $result->sId;
				$balance = (float) $result->sRefWallet;
				$oldbalance = $balance;
				$amount = $bonus;
            	$newbalance = $oldbalance + $amount;
				$servicename = "Smile Bonus";
    			$servicsdesc = "Smile Bonus Of N{$bonus} For:  {$servicsdesc}";
				$status = 0;
				$date=date("Y-m-d H:i:s");
				$ref = "BONUS-".time().rand(999,9999);
			
            
                //Record Transaction
    			$sql2 = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
    			$query2 = $dbh->prepare($sql2);
    			$query2->bindParam(':user',$userId,PDO::PARAM_INT);
    			$query2->bindParam(':ref',$ref,PDO::PARAM_STR);
    			$query2->bindParam(':sn',$servicename,PDO::PARAM_STR);
    			$query2->bindParam(':sd',$servicsdesc,PDO::PARAM_STR);
    			$query2->bindParam(':a',$amount,PDO::PARAM_STR);
    			$query2->bindParam(':s',$status,PDO::PARAM_INT);
    			$query2->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
    			$query2->bindParam(':nb',$newbalance,PDO::PARAM_STR);
    			$query2->bindParam(':d',$date,PDO::PARAM_STR);
    			$query2->execute();
    
    			$lastInsertId = $dbh->lastInsertId();
    			if($lastInsertId){
    				//Update Account Type & Balance
    				$sql3 = "UPDATE subscribers SET sRefWallet=:bal WHERE sId=:id";
    				$query3 = $dbh->prepare($sql3);
    				$query3->bindParam(':id',$userId,PDO::PARAM_INT);
    				$query3->bindParam(':bal',$newbalance,PDO::PARAM_STR);
    				$query3->execute();
    				return 0;
    			}
            }
            
        }

        //------------------------------------------------------------------------------------------------------------------
        // PAYVESSEL
        //------------------------------------------------------------------------------------------------------------------

        
		//Record Payvessel Transaction 
		public function recordPayvesselTransaction($userid,$servicename,$servicedesc,$ref,$amountopay,$oldbalance,$newbalance,$status){
			$dbh=$this->connect();
			$userid=(float) $userid;
			$status=(float) $status;
            $date=date("Y-m-d H:i:s");
            
            //Check If Discount Already Exist
			$queryC=$dbh->prepare("SELECT transref FROM transactions WHERE transref=:transref");
			$queryC->bindParam(':transref',$ref,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 1;}
			
            //If transaction was successful, debit user wallet
            $sqlD = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $queryD->execute();

            //Record Transaction
			$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
            $query = $dbh->prepare($sql);
            $query->bindParam(':user',$userid,PDO::PARAM_INT);
            $query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $query->bindParam(':a',$amountopay,PDO::PARAM_STR);
            $query->bindParam(':s',$status,PDO::PARAM_INT);
            $query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
            $query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
            $query->bindParam(':d',$date,PDO::PARAM_STR);
            $query->execute();

            $lastInsertId = $dbh->lastInsertId();
			if($lastInsertId){return 0;}else{return 1;}
		}
		
		
		//Validate Payvessel Transaction
		public function verifyPayvesselRef($email,$token,$payload){
			$dbh=$this->connect();
			
            //Get Api Key
            //Get Configuration Details
            $sql = "SELECT * FROM apiconfigs";
            $query = $dbh->prepare($sql);
            $query->execute(); 
            $result=$query->fetchAll(PDO::FETCH_OBJ);
           
            $payvesselSecrete = $this->getConfigValue($result,"payvesselSecret");
            $payvesselCharges = (float) $this->getConfigValue($result,"payvesselCharges");
            $payvesselChargesType = $this->getConfigValue($result,"payvesselChargesType");
            
            $hash=$this->computePayvesselHash($payload, $payvesselSecrete);
            
            
            //Verify Notification Details
            if($token == $hash):
            
                $sqlA = "SELECT * FROM subscribers WHERE sEmail=:e";
                $queryA = $dbh->prepare($sqlA);
                $queryA->bindParam(':e',$email,PDO::PARAM_STR);
                $queryA->execute();
                $resultA=$queryA->fetch(PDO::FETCH_OBJ);
                if($queryA->rowCount() > 0):
                    $response = array();
                    $response["status"] = "success";
                    $response["userid"] = $resultA->sId;
                    $response["name"] = $resultA->sLname ." ". $resultA->sFname;
                    $response["balance"] = $resultA->sWallet;
                    $response["useremail"] = $resultA->sEmail;
                    $response["charges"] = $payvesselCharges;
                    $response["chargestype"] = $payvesselChargesType;
                    return (object) $response;
                endif;
            
            else:
                $response = array();
                $response["status"] = "fail";
                $response["message"] = "Not Found";
                return (object) $response;
            endif;
            
		}
		
		//Compute Payvessel Hash
		 public function computePayvesselHash($stringifiedData, $clientSecret) {
          $computedHash = hash_hmac('sha512', $stringifiedData, $clientSecret);
          return $computedHash;
        }
        
        // Update Transaction Status With Server Log
        public function updateTransactionWithApiResponseLog($ref, $response) {
            // Connect to the database
            $dbh = $this->connect();

            // Decode the JSON response
            $responseArray = json_decode($response, true);

            // Extract the api_response from the decoded JSON
            $apiResponse = $responseArray['api_response'];

            // Update the database with only the api_response
            $sqlD = "UPDATE transactions SET api_response_log=:servicedesc WHERE transref=:ref";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':servicedesc', $apiResponse, PDO::PARAM_STR);
            $queryD->bindParam(':ref', $ref, PDO::PARAM_STR);
            $queryD->execute();
        }


        

        // Debit My User Before Transaction
        // Get User Current Wallet, Debit It And Record The Transaction As Processing
        public function debitMyUserBeforeTransaction($userid,$deibt,$servicename,$servicedesc,$ref,$status){
            $dbh=$this->connect();
			$userid=(float) $userid;
			$date=date("Y-m-d H:i:s");            

            
            $sqlD = "
                    START TRANSACTION;
                        SELECT @oldwallet := sWallet FROM subscribers WHERE sId=:id LIMIT 1 FOR UPDATE;
                        SELECT @newwallet := @oldwallet - $deibt;
                        UPDATE subscribers SET sWallet=@newwallet WHERE sId=:id;
                        INSERT INTO transactions SET sId=:id,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,
                        status=:s,oldbal=@oldwallet,newbal=@newwallet,date=:d;
                    COMMIT;
            ";

            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':id',$userid,PDO::PARAM_INT);
            $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
            $queryD->bindParam(':sn',$servicename,PDO::PARAM_STR);
            $queryD->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
            $queryD->bindParam(':a',$deibt,PDO::PARAM_STR);
            $queryD->bindParam(':s',$status,PDO::PARAM_INT);
            $queryD->bindParam(':d',$date,PDO::PARAM_STR);
            if($queryD->execute()){ return "success";}
            else{return "fail";}

        }

        //Update Transaction Status
        public function updateMyTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog){
            
            $dbh=$this->connect();
            $responseArray = json_decode($serverlog, true);

            // Extract the api_response from the decoded JSON
            // $apiResponse = $responseArray['api_response'];
            // Extract the api_response or response from the decoded JSON
            if (isset($responseArray['api_response'])) {
                $apiResponse = $responseArray['api_response'];
            } elseif (isset($responseArray['response'])) {
                $apiResponse = $responseArray['response'];
            } elseif (isset($responseArray['response_description'])) {
                $apiResponse = $responseArray['response_description'];
            } else {
                // Handle the case where neither key is present
                $apiResponse = null; // Or any default value you want
            }

			$userid=(float) $userid;
			$status=(float) $status;
			$amountopay=(float) $amountopay;

            //If Transaction Failed, Refund User Since He Has Been Debited Already 
            
            if($status == 1){
                
                //Refund Amount To Wallet
                $sqlW = "
                START TRANSACTION;
                    SELECT @oldwallet := sWallet FROM subscribers WHERE sId=:id LIMIT 1 FOR UPDATE;
                    SELECT @newwallet := @oldwallet + $amountopay;
                    UPDATE subscribers SET sWallet=@newwallet WHERE sId=:id;
                    UPDATE transactions SET oldbal=:ubal,newbal=:ubal,status=:status,profit=0,api_response_log=:elog WHERE transref=:ref;
                COMMIT;
                ";

                //UPDATE transactions SET oldbal=newbal,status=:status,profit=0,api_response_log=:elog WHERE newbal>oldbal AND transref=:ref;
                //UPDATE transactions SET newbal=oldbal,status=:status,profit=0,api_response_log=:elog WHERE oldbal>newbal AND transref=:ref;
            
                  
                $queryW = $dbh->prepare($sqlW);
                $queryW->bindParam(':id',$userid,PDO::PARAM_INT);
                $queryW->bindParam(':status',$status,PDO::PARAM_INT);
                $queryW->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryW->bindParam(':ubal',$userbalance,PDO::PARAM_STR);
                $queryW->bindParam(':elog',$apiResponse,PDO::PARAM_STR);
                $queryW->execute();
            }
            else{
                //Update Transaction Status
                $sqlD = "UPDATE transactions SET status=:status,profit=:profit,api_response_log=:elog WHERE sId=:user AND transref=:ref";
                $queryD = $dbh->prepare($sqlD);
                $queryD->bindParam(':user',$userid,PDO::PARAM_INT);
                $queryD->bindParam(':status',$status,PDO::PARAM_INT);
                $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryD->bindParam(':profit',$profit,PDO::PARAM_STR);
                $queryD->bindParam(':elog',$apiResponse,PDO::PARAM_STR);
                $queryD->execute();
            }
        }

        //Update Electric Transaction Status
        public function updateMyElectricTransactionStatus($userid,$ref,$userbalance,$amountopay,$status,$profit,$serverlog){
            
            $dbh=$this->connect();
            // $responseArray = json_decode($serverlog, true);

            // // Extract the api_response from the decoded JSON
            // // $apiResponse = $responseArray['api_response'];
            // // Extract the api_response or response from the decoded JSON
            // if (isset($responseArray['api_response'])) {
            //     $apiResponse = $responseArray['api_response'];
            // } elseif (isset($responseArray['response'])) {
            //     $apiResponse = $responseArray['response'];
            // } else {
            //     // Handle the case where neither key is present
            //     $apiResponse = null; // Or any default value you want
            // }

			$userid=(float) $userid;
			$status=(float) $status;
			$amountopay=(float) $amountopay;

            //If Transaction Failed, Refund User Since He Has Been Debited Already 
            
            if($status == 1){
                
                //Refund Amount To Wallet
                $sqlW = "
                START TRANSACTION;
                    SELECT @oldwallet := sWallet FROM subscribers WHERE sId=:id LIMIT 1 FOR UPDATE;
                    SELECT @newwallet := @oldwallet + $amountopay;
                    UPDATE subscribers SET sWallet=@newwallet WHERE sId=:id;
                    UPDATE transactions SET oldbal=:ubal,newbal=:ubal,status=:status,profit=0,api_response_log=:elog WHERE transref=:ref;
                COMMIT;
                ";

                //UPDATE transactions SET oldbal=newbal,status=:status,profit=0,api_response_log=:elog WHERE newbal>oldbal AND transref=:ref;
                //UPDATE transactions SET newbal=oldbal,status=:status,profit=0,api_response_log=:elog WHERE oldbal>newbal AND transref=:ref;
            
                  
                $queryW = $dbh->prepare($sqlW);
                $queryW->bindParam(':id',$userid,PDO::PARAM_INT);
                $queryW->bindParam(':status',$status,PDO::PARAM_INT);
                $queryW->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryW->bindParam(':ubal',$userbalance,PDO::PARAM_STR);
                $queryW->bindParam(':elog',$serverlog,PDO::PARAM_STR);
                $queryW->execute();
            }
            else{
                //Update Transaction Status
                $sqlD = "UPDATE transactions SET status=:status,profit=:profit,api_response_log=:elog WHERE sId=:user AND transref=:ref";
                $queryD = $dbh->prepare($sqlD);
                $queryD->bindParam(':user',$userid,PDO::PARAM_INT);
                $queryD->bindParam(':status',$status,PDO::PARAM_INT);
                $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
                $queryD->bindParam(':profit',$profit,PDO::PARAM_STR);
                $queryD->bindParam(':elog',$serverlog,PDO::PARAM_STR);
                $queryD->execute();
            }
        }

        //Delete Transaction Due To Insufficient Fund Detected On Multiple Request Sent
        public function deleteTransactionDueToInsufficientBalance($ref){
            
            $dbh=$this->connect();
			
            $sqlD = "DELETE FROM transactions WHERE transref=:ref";
            $queryD = $dbh->prepare($sqlD);
            $queryD->bindParam(':ref',$ref,PDO::PARAM_STR);
            $queryD->execute();
            
        }


	}

?> 