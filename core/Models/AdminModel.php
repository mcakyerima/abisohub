<?php

	class AdminModel extends Model{

		//----------------------------------------------------------------------------------------------------------------
		// System Users Account Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Create New System User Account
		public function createAccount($name,$username,$key,$role){
			$dbh=$this->connect();
			$role=(float) $role;

			//Check If Username Already Exist
			$queryC=$dbh->prepare("SELECT sysId FROM sysusers WHERE sysUsername=:username");
			$queryC->bindParam(':username',$username,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}

			//If Not Exist, Create New User
			$sql="INSERT INTO  sysusers(sysName,sysUsername,sysToken,sysRole) VALUES(:name,:username,:key,:role)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':name',$name,PDO::PARAM_STR);
            $query->bindParam(':username',$username,PDO::PARAM_STR);
            $query->bindParam(':key',$key,PDO::PARAM_STR);
            $query->bindParam(':role',$role,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		//Get All Account Details
		public function getAccounts(){
			$dbh=$this->connect();
			$sql = "SELECT * from sysusers";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $results;} else{return 1;}
		}

		//Get Account Details By ID
		public function getAccountById($id){
			$id=(float) $id;
			$dbh=$this->connect();
			$sql = "SELECT * FROM sysusers WHERE sysId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $result;} else{return 1;}
		}

		//Update User Account By ID
		public function updateAccountStatus($id,$status){
			$id=(float) $id;
			$status=(float) $status;
			if($status == 1){$status=0; }else{$status=1; }
			
			$dbh=$this->connect();
			$sql="UPDATE sysusers SET sysStatus=$status WHERE sysId=$id ";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		//Update Admin Profile Password
		public function updateAdminAccount($id,$name,$oldKey,$newKey){
			
			$dbh=$this->connect();
			$id=(float) $id;

			if($newKey == ""){$newKey=$oldKey;}

			$c="SELECT sysToken FROM sysusers WHERE sysToken=:p AND sysId=$id";
	    	$queryC = $dbh->prepare($c);
	    	$queryC->bindParam(':p',$oldKey,PDO::PARAM_STR);
	     	$queryC->execute();
	      	$result=$queryC->fetch(PDO::FETCH_ASSOC);

	      	if($queryC->rowCount() > 0){
	          
	          $sql="UPDATE sysusers SET sysToken=:p,sysName=:name WHERE sysId=$id";
			  $query = $dbh->prepare($sql);
			  $query->bindParam(':p',$newKey,PDO::PARAM_STR);
			  $query->bindParam(':name',$name,PDO::PARAM_STR);
			  $query->execute();
			  $_SESSION["sysName"]=$name;
			  return 0;
	      	}
	      	else{return 1;}
			
		}


		//Update Admin Profile Pin
		public function updateAdminAccountStatus($id,$loginstatus,$loginpin,$newpin){
			
			$dbh=$this->connect();
			$id=(float) $id;

			if($newpin == ""){$newpin=$loginpin;}

			$newpin=substr(sha1(md5($newpin)), 3, 10);
			$loginpin=substr(sha1(md5($loginpin)), 3, 10);

			$c="SELECT sysPinToken FROM sysusers WHERE sysPinToken=:p AND sysId=$id";
	    	$queryC = $dbh->prepare($c);
	    	$queryC->bindParam(':p',$loginpin,PDO::PARAM_STR);
	     	$queryC->execute();
	      	$result=$queryC->fetch(PDO::FETCH_ASSOC);

	      	if($queryC->rowCount() > 0){
	          
	          $sql="UPDATE sysusers SET sysPinToken=:p,sysPinStatus=:sta WHERE sysId=$id";
			  $query = $dbh->prepare($sql);
			  $query->bindParam(':p',$newpin,PDO::PARAM_STR);
			  $query->bindParam(':sta',$loginstatus,PDO::PARAM_STR);
			  $query->execute();
			  return 0;
			  
	      	}
	      	else{return 1;}
			
		}


		//----------------------------------------------------------------------------------------------------------------
		//	Site Settings
		//----------------------------------------------------------------------------------------------------------------
		
		//Get Site Setting
		public function getSiteSettings(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM sitesettings WHERE sId=1";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            return $results;
		}

		//Update Contact Setting
		public function updateContactSetting($phone,$email,$whatsapp,$whatsappgroup,$instagram,$facebook,$twitter,$telegram){
			$dbh=$this->connect();
			$sql="UPDATE sitesettings SET phone=:p,email=:e,whatsapp=:w,whatsappgroup=:wg,instagram=:ig,facebook=:fb,twitter=:t,telegram=:te WHERE sId=1";
			$query = $dbh->prepare($sql);
			$query->bindParam(':p',$phone,PDO::PARAM_STR);
			$query->bindParam(':e',$email,PDO::PARAM_STR);
			$query->bindParam(':w',$whatsapp,PDO::PARAM_STR);
			$query->bindParam(':wg',$whatsappgroup,PDO::PARAM_STR);
			$query->bindParam(':ig',$instagram,PDO::PARAM_STR);
			$query->bindParam(':fb',$facebook,PDO::PARAM_STR);
			$query->bindParam(':t',$twitter,PDO::PARAM_STR);
			$query->bindParam(':te',$telegram,PDO::PARAM_STR);
			$query->execute();
			return 0;
		}

		//Update Site Setting
		public function updateSiteSetting($sitename,$siteurl,$apidocumentation,$referalupgradebonus,$referalairtimebonus,$referaldatabonus,$referalwalletbonus,$referalcablebonus,$referalexambonus,$referalmeterbonus,$wallettowalletcharges,$agentupgrade,$vendorupgrade,$accountname,$accountno,$bankname,$electricity,$airtimemin,$airtimemax){
			$dbh=$this->connect();
			$sql="UPDATE sitesettings SET sitename=:sn,siteurl=:u,agentupgrade=:au,vendorupgrade=:vu,apidocumentation=:ad,referalupgradebonus=:rub,referalairtimebonus=:rab,referaldatabonus=:rdb,referalwalletbonus=:rwb,referalcablebonus=:rcb,referalexambonus=:reb,referalmeterbonus=:rmb,wallettowalletcharges=:wwc,accountname=:accna,accountno=:accno,bankname=:bnkna,electricitycharges=:electc,airtimemin=:amin,airtimemax=:amax WHERE sId=1";
			$query = $dbh->prepare($sql);
			$query->bindParam(':sn',$sitename,PDO::PARAM_STR);
			$query->bindParam(':u',$siteurl,PDO::PARAM_STR);
			$query->bindParam(':au',$agentupgrade,PDO::PARAM_STR);
			$query->bindParam(':vu',$vendorupgrade,PDO::PARAM_STR);
			$query->bindParam(':ad',$apidocumentation,PDO::PARAM_STR);
			$query->bindParam(':rub',$referalupgradebonus,PDO::PARAM_STR);
			$query->bindParam(':rab',$referalairtimebonus,PDO::PARAM_STR);
			$query->bindParam(':rdb',$referaldatabonus,PDO::PARAM_STR);
			$query->bindParam(':rwb',$referalwalletbonus,PDO::PARAM_STR);
			$query->bindParam(':rcb',$referalcablebonus,PDO::PARAM_STR);
			$query->bindParam(':reb',$referalexambonus,PDO::PARAM_STR);
			$query->bindParam(':rmb',$referalmeterbonus,PDO::PARAM_STR);
			$query->bindParam(':wwc',$wallettowalletcharges,PDO::PARAM_STR);
			$query->bindParam(':accno',$accountno,PDO::PARAM_STR);
			$query->bindParam(':accna',$accountname,PDO::PARAM_STR);
			$query->bindParam(':bnkna',$bankname,PDO::PARAM_STR);
			$query->bindParam(':electc',$electricity,PDO::PARAM_STR);
			$query->bindParam(':amin',$airtimemin,PDO::PARAM_STR);
			$query->bindParam(':amax',$airtimemax,PDO::PARAM_STR);
			$query->execute();
			return 0;
		}

		//Update Site Style
		public function updateSiteStyleSetting($sitecolor,$loginstyle,$homestyle){
			$dbh=$this->connect();
			$sql="UPDATE sitesettings SET sitecolor=:sc,logindesign=:ls,homedesign=:hs WHERE sId=1";
			$query = $dbh->prepare($sql);
			$query->bindParam(':sc',$sitecolor,PDO::PARAM_STR);
			$query->bindParam(':ls',$loginstyle,PDO::PARAM_STR);
			$query->bindParam(':hs',$homestyle,PDO::PARAM_STR);
			$query->execute();
			return 0;
		}
 
		//Update Network Setting
		public function updateNetworkSetting($network,$general,$vtuStatus,$sharesellStatus,$airtimepin,$datapin,$sme,$gifting,$share,$corporate,$networkid,$vtuId,$sharesellId,$smeId,$giftingId,$shareId,$corporateId){
			$dbh=$this->connect();
			$id = (float) $network;
			
			$sql="UPDATE networkid SET networkStatus=:g, vtuStatus=:vs, sharesellStatus=:sss, airtimepinStatus=:ap, datapinStatus=:dp, smeStatus=:s, giftingStatus=:gi, shareStatus=:ss, corporateStatus=:c, networkId=:nid, vtuId=:vtuid, sharesellId=:sharesellid,smeId=:smeid,giftingId=:giftid,shareId=:shareid,corporateId=:ccid WHERE nId = $id";
			$query = $dbh->prepare($sql); 
			$query->bindParam(':g',$general,PDO::PARAM_STR); 
			$query->bindParam(':vs',$vtuStatus,PDO::PARAM_STR); 
			$query->bindParam(':sss',$sharesellStatus,PDO::PARAM_STR); 
			$query->bindParam(':ap',$airtimepin,PDO::PARAM_STR); 
			$query->bindParam(':dp',$datapin,PDO::PARAM_STR); 
			$query->bindParam(':s',$sme,PDO::PARAM_STR); 
			$query->bindParam(':gi',$gifting,PDO::PARAM_STR); 
			$query->bindParam(':ss',$share,PDO::PARAM_STR); 
			$query->bindParam(':c',$corporate,PDO::PARAM_STR); 
			$query->bindParam(':nid',$networkid,PDO::PARAM_STR); 
			$query->bindParam(':vtuid',$vtuId,PDO::PARAM_STR); 
			$query->bindParam(':sharesellid',$sharesellId,PDO::PARAM_STR); 
			$query->bindParam(':smeid',$smeId,PDO::PARAM_STR); 
			$query->bindParam(':ccid',$corporateId,PDO::PARAM_STR); 
			$query->bindParam(':giftid',$giftingId,PDO::PARAM_STR); 
			$query->bindParam(':shareid',$shareId,PDO::PARAM_STR); 
			$query->execute();

			return 0;
		}
		

		//----------------------------------------------------------------------------------------------------------------
		//	API Management
		//----------------------------------------------------------------------------------------------------------------
		//Get API Setting
		public function getApiConfiguration(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM apiconfigs";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get API Link Setting
		public function getApiConfigurationLinks(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM apilinks";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Update API Setting
		public function updateApiConfiguration(){
			$dbh=$this->connect();
			$count = COUNT($_POST);
			
			if($count > 0){

				foreach($_POST AS $index => $value){
					$sql = "UPDATE apiconfigs SET value=:d WHERE name=:n";
					$query = $dbh->prepare($sql);
					$query->bindParam(':n',$index,PDO::PARAM_STR);
					$query->bindParam(':d',$value,PDO::PARAM_STR);
					$query->execute();
				}

				return 0;
			}
			else{
				return 1;
			}

		}

		//Add Notification
		public function addNewApiDetails($providername,$providerurl,$service,$code){
			$dbh=$this->connect(); 
			$coder = date("Hymd") . date("d");

			if($coder <> $code){return 1;}

			$c="SELECT * FROM apilinks WHERE value=:v AND type=:t";
	    	$queryC = $dbh->prepare($c);
	    	$queryC->bindParam(':v',$providerurl,PDO::PARAM_STR);
			$queryC->bindParam(':t',$service,PDO::PARAM_STR);
	     	$queryC->execute();
	      	
	      	if($queryC->rowCount() > 0){
				return 2;
			}
			else{
				$sql="INSERT INTO apilinks (`name`,`value`,`type`) VALUES (:n,:v, :t)";
				$query = $dbh->prepare($sql);
				$query->bindParam(':n',$providername,PDO::PARAM_STR);
				$query->bindParam(':v',$providerurl,PDO::PARAM_STR);
				$query->bindParam(':t',$service,PDO::PARAM_STR);
				$query->execute();
				$lastInsertId = $dbh->lastInsertId();
				if($lastInsertId){return 0;} else{return 3;}
			}

		}



		//----------------------------------------------------------------------------------------------------------------
		//	Notification Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Send Email To User
		public function sendEmailToUser($subject,$email,$message){
			$subject = $subject ." (".$this->sitename.")";
			self::sendMail($email,$subject,$message);
			return 0;
		}
		
		//----------------------------------------------------------------------------------------------------------------
		//	Notification Management
		//----------------------------------------------------------------------------------------------------------------
		//Get Notification Status
		public function getNotificationStatus(){
			$dbh=$this->connect();
			$sql = "SELECT notificationStatus FROM sitesettings WHERE sId=1";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            return $results;
		}

		//Update Notification Status
		public function updateNotificationStatus($notificationstatus){
			$dbh=$this->connect();
			$sql="UPDATE sitesettings SET notificationStatus=:s WHERE sId=1";
			$query = $dbh->prepare($sql);
			$query->bindParam(':s',$notificationstatus,PDO::PARAM_STR);
			$query->execute();
			return 0;
		}
		
		//Get API Notification
		public function getNotifications(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM notifications";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Add Notification
		public function addNotification($subject,$msgfor,$message){
			$dbh=$this->connect();
			$sql="INSERT INTO notifications SET subject=:s,msgfor=:f,message=:m";
			$query = $dbh->prepare($sql);
			$query->bindParam(':s',$subject,PDO::PARAM_STR);
			$query->bindParam(':f',$msgfor,PDO::PARAM_INT);
			$query->bindParam(':m',$message,PDO::PARAM_STR);
			$query->execute();
			$lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		///Delete Notification
		public function deleteNotification($id){
			$dbh=$this->connect();
			$sql = "DELETE FROM notifications WHERE msgId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		//----------------------------------------------------------------------------------------------------------------
		//	Airtime Discount Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Network
		public function getNetworks(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM networkid ORDER BY nId ASC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get Airtime Discount
		public function getAirtimeDiscount(){
			$dbh=$this->connect();
			$sql = "SELECT a.*,b.network,b.nId FROM airtime a, networkid b WHERE a.aNetwork=b.nId";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}


		//Add Airtime Discount
		public function addAirtimeDiscount($network,$networktype,$buydiscount,$userdiscount,$agentdiscount,$vendordiscount){
			$dbh=$this->connect();

			//Check If Discount Already Exist
			$queryC=$dbh->prepare("SELECT aNetwork FROM airtime WHERE aNetwork=:n AND aType=:tt");
			$queryC->bindParam(':n',$network,PDO::PARAM_STR);
			$queryC->bindParam(':tt',$networktype,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New Discount
			$sql="INSERT INTO airtime(aNetwork,aType,aBuyDiscount,aUserDiscount,aAgentDiscount,aVendorDiscount) VALUES(:n,:ny,:b,:u,:a,:v)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':ny',$networktype,PDO::PARAM_STR);
            $query->bindParam(':b',$buydiscount,PDO::PARAM_STR);
            $query->bindParam(':u',$userdiscount,PDO::PARAM_STR);
            $query->bindParam(':a',$agentdiscount,PDO::PARAM_STR);
            $query->bindParam(':v',$vendordiscount,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		
		//Update Airtime Discount
		public function updateAirtimeDiscount($id,$network,$networktype,$buydiscount,$userdiscount,$agentdiscount,$vendordiscount){
			$dbh=$this->connect();
			$id= (int) base64_decode($id);
			$sql="UPDATE airtime SET aNetwork=:n,aType=:nt,aBuyDiscount=:b,aUserDiscount=:u,aAgentDiscount=:a,aVendorDiscount=:v WHERE aId=$id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':nt',$networktype,PDO::PARAM_STR);
            $query->bindParam(':b',$buydiscount,PDO::PARAM_STR);
            $query->bindParam(':u',$userdiscount,PDO::PARAM_STR);
            $query->bindParam(':a',$agentdiscount,PDO::PARAM_STR);
            $query->bindParam(':v',$vendordiscount,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
            
		} 

		//----------------------------------------------------------------------------------------------------------------
		// Alpha Topup Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Add Alpha Topup
		public function addAlphaTopup($buying,$selling,$agent,$vendor){
			$dbh=$this->connect();

			//Check If Topup Already Exist
			$queryC=$dbh->prepare("SELECT * FROM alphatopupprice WHERE buyingPrice=:bn AND sellingPrice=:sn");
			$queryC->bindParam(':bn',$buying,PDO::PARAM_STR);
			$queryC->bindParam(':sn',$selling,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New Discount
			$sql="INSERT INTO alphatopupprice(buyingPrice,SellingPrice,agent,vendor) VALUES(:b,:s,:a,:v)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':b',$buying,PDO::PARAM_STR);
            $query->bindParam(':s',$selling,PDO::PARAM_STR);
            $query->bindParam(':a',$agent,PDO::PARAM_STR);
			$query->bindParam(':v',$vendor,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		//Update Alpha Topup
		public function updateAlphaTopup($id,$buying,$selling,$agent,$vendor){
			$dbh=$this->connect();
			$id= (int) base64_decode($id);
			$sql="UPDATE alphatopupprice SET buyingPrice=:bp,sellingPrice=:sp,agent=:a,vendor=:v WHERE alphaId=$id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':bp',$buying,PDO::PARAM_INT);
            $query->bindParam(':sp',$selling,PDO::PARAM_INT);
            $query->bindParam(':a',$agent,PDO::PARAM_STR);
			$query->bindParam(':v',$vendor,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
            

		}

		///Delete A Data Plan
		public function deleteAlphaTopup($id){
			$dbh=$this->connect();
			$id= (int) base64_decode($id);
			$sql = "DELETE FROM alphatopupprice WHERE alphaId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		
		//Get Alpha Topup
		public function getAlphaTopup(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM alphatopupprice";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get All Pending Alpha Transactions
		public function getPendingAlphaOrder(){
			$dbh=$this->connect();
			$sql = "SELECT a.sFname,a.sPhone,a.sEmail,a.sType,b.* 
			FROM subscribers a, transactions b WHERE a.sId=b.sId AND b.status=2 ORDER BY b.date DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Complete Alpha Topup Request
		public function completeAlphaTopupRequest($id){
			$dbh=$this->connect();
			$sql = "UPDATE transactions SET status = 0 WHERE tId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		//----------------------------------------------------------------------------------------------------------------
		//	Recharge Card Pin Discount Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get Recharge Card Pin Discount
		public function getRechargeCardPinDiscount(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM airtimepin a, networkid b WHERE a.aNetwork=b.networkid";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Get Recharge Card Pins
		public function getAirtimePinStocks(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM airtimepinstock a, networkid b WHERE a.network=b.networkid ORDER BY date DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Get Recharge Card Pins
		public function clearUsedRechargeCardPins(){
			$dbh=$this->connect();
			$sql = "DELETE FROM airtimepinstock WHERE status <> 'Unused'";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}
		
		//Upload Recharge Cards
		public function uploadRechargeCardPins($network,$amount,$pincolumn,$serialnocolumn,$records){
			
			$dbh=$this->connect();
			$pincolumn = (int) $pincolumn; $pincolumn--;
			$serialnocolumn = (int) $serialnocolumn; $serialnocolumn--;
			$date=date("Y-m-d H:i:s");
			$status = "Unused";
			$soldto = "Not Sold Yet";
    
            $recordCounter=0;
            
			$sql="
			START TRANSACTION;
			INSERT INTO airtimepinstock (network,amount,tokens,serial,status,soldto,date) 
			VALUES ";
			
			//Get Pins From Excel Records ANd form SL Statement
			foreach($records AS $pins){
			    if(!empty($pins[$pincolumn])){
			        $sql.="('{$network}','{$amount}','{$pins[$pincolumn]}','{$pins[$serialnocolumn]}','{$status}','{$soldto}','{$date}'),";
			    }
			    
			    $recordCounter++;
			}
			
			$sql = rtrim($sql,",");
			
			$sql.="; COMMIT;";
			
			$query = $dbh->prepare($sql);
            $query->execute();
            
            if($recordCounter > 0){return 0;} else{return 1;}
		}


		//Add Recharge Card Pin Discount
		public function addRechargeCardPinDiscount($network,$amount,$buyprice,$userdiscount,$agentdiscount,$vendordiscount,$loadpin,$checkbal,$planid){
			$dbh=$this->connect();

			//Check If Discount Already Exist
			$queryC=$dbh->prepare("SELECT aNetwork FROM airtimepin WHERE aNetwork=:n AND planSize=:plan");
			$queryC->bindParam(':n',$network,PDO::PARAM_STR);
			$queryC->bindParam(':plan',$amount,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New Discount
			$sql="INSERT INTO airtimepin (planSize,aNetwork,aBuyPrice,aUserPrice,aAgentPrice,aVendorPrice,loadpin,checkbalance,planid) VALUES(:p,:n,:b,:u,:a,:v,:l,:c,:pl)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':u',$userdiscount,PDO::PARAM_STR);
            $query->bindParam(':a',$agentdiscount,PDO::PARAM_STR);
            $query->bindParam(':v',$vendordiscount,PDO::PARAM_STR);
            $query->bindParam(':p',$amount,PDO::PARAM_STR);
            $query->bindParam(':b',$buyprice,PDO::PARAM_STR);
            $query->bindParam(':l',$loadpin,PDO::PARAM_STR);
            $query->bindParam(':c',$checkbal,PDO::PARAM_STR);
            $query->bindParam(':pl',$planid,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}


		//Update Recharge Card Pin Discount
		public function updateRechargeCardPinDiscount($network,$amount,$buyprice,$userdiscount,$agentdiscount,$vendordiscount,$loadpin,$checkbal,$planid){
			$dbh=$this->connect();
			$sql="UPDATE airtimepin SET planSize=:p,aNetwork=:n,aBuyPrice=:b,aUserPrice=:u,aAgentPrice=:a,aVendorPrice=:v,loadpin=:l,checkbalance=:c,planid=:pl WHERE aNetwork=:n AND planSize=:p";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':u',$userdiscount,PDO::PARAM_STR);
            $query->bindParam(':a',$agentdiscount,PDO::PARAM_STR);
            $query->bindParam(':v',$vendordiscount,PDO::PARAM_STR);
            $query->bindParam(':p',$amount,PDO::PARAM_STR);
            $query->bindParam(':b',$buyprice,PDO::PARAM_STR);
            $query->bindParam(':l',$loadpin,PDO::PARAM_STR);
            $query->bindParam(':c',$checkbal,PDO::PARAM_STR);
            $query->bindParam(':pl',$planid,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
		}

		 //Get Number Of Available Pins
		 public function getNumberOfAvailablePins(){
			$dbh=$this->connect();
			$available = array();
			
			$rechargeCardPlans = $this->getRechargeCardPinDiscount();
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


		//----------------------------------------------------------------------------------------------------------------
		//	Data Plan Stock Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get Data Card Pins
		public function getDataPinStocks(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM datapinstock a, networkid b WHERE a.network=b.networkid ORDER BY date DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Get Data Card Pins
		public function clearUsedDataCardPins(){
			$dbh=$this->connect();
			$sql = "DELETE FROM datapinstock WHERE status <> 'Unused'";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}
		
		//Upload Data Cards
		public function uploadDataCardPins($network,$amount,$pincolumn,$serialnocolumn,$records){
			
			$dbh=$this->connect();
			$pincolumn = (int) $pincolumn; $pincolumn--;
			$serialnocolumn = (int) $serialnocolumn; $serialnocolumn--;
			$date=date("Y-m-d H:i:s");
			$status = "Unused";
			$soldto = "Not Sold Yet";
    
            $recordCounter=0;
            
			$sql="
			START TRANSACTION;
			INSERT INTO datapinstock (network,amount,tokens,serial,status,soldto,date) 
			VALUES ";
			
			//Get Pins From Excel Records ANd form SL Statement
			foreach($records AS $pins){
			    if(!empty($pins[$pincolumn])){
					if(empty($pins[$serialnocolumn])){$serialnocolumn = "SE".time().rand(1000,9999);}
					else{$serialnocolumn = $pins[$serialnocolumn];}
			        $sql.="('{$network}','{$amount}','{$pins[$pincolumn]}','{$serialnocolumn}','{$status}','{$soldto}','{$date}'),";
			    }
			    
			    $recordCounter++;
			}
			
			$sql = rtrim($sql,",");
			
			$sql.="; COMMIT;";
			
			$query = $dbh->prepare($sql);
            $query->execute();
            
            if($recordCounter > 0){return 0;} else{return 1;}
		}
 

		//Get Number Of Available Data Pins
		 public function getNumberOfAvailableDataPins(){
			$dbh=$this->connect();
			$available = array();
			
			$dataCardPlans = $this->getDataPins();
			foreach($dataCardPlans AS $plans){
			        
			        $network = $plans->networkid;
			        $name = $plans->name ." (".$plans->type.")";
			        
			        
			    	$sql = "SELECT COUNT(tId) AS availablepins FROM datapinstock WHERE network=:n AND amount=:am AND status = 'Unused' ";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':n',$network,PDO::PARAM_STR);
                    $query->bindParam(':am',$name,PDO::PARAM_STR);
                    $query->execute();
                    $results=$query->fetch(PDO::FETCH_OBJ);
                    $availablepins = $results->availablepins;
                    
                    array_push($available,["network"=>$plans->network,"name"=>$name,"pins"=>$availablepins]);
                    
			}
			
			return $available;
		
		}


		//----------------------------------------------------------------------------------------------------------------
		//	Data Plan Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get Data Plans
		public function getDataPlans(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM dataplans a, networkid b WHERE a.datanetwork=b.nId";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get Data Pins
		public function getDataPins(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM datapins a, networkid b WHERE a.datanetwork=b.nId";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}


		//Add Data Plans
		public function addDataPlan($network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice){
			$dbh=$this->connect();

			//Check If Username Already Exist
			$queryC=$dbh->prepare("SELECT planid FROM dataplans WHERE planid=:p");
			$queryC->bindParam(':p',$planid,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New User
			$sql="INSERT INTO  dataplans (datanetwork,name,type,planid,day,price,userprice,agentprice,vendorprice) 
			VALUES(:n,:d,:dt,:p,:du,:pr,:up,:ap,:vp)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':dt',$datatype,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		//Update Data Plan
		public function updateDataPlan($plan,$network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice){
			$dbh=$this->connect();

			
			//If Not Exist, Create New User
			$sql="UPDATE dataplans SET datanetwork=:n,name=:d,type=:dt,planid=:p,day=:du,price=:pr,userprice=:up,agentprice=:ap,vendorprice=:vp WHERE pId=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id',$plan,PDO::PARAM_STR);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':dt',$datatype,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
            
		}

		///Delete A Data Plan
		public function deleteDataPlan($id){
			$dbh=$this->connect();
			$sql = "DELETE FROM dataplans WHERE pId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		//Add Data Pins
		public function addDataPin($network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice,$loadpin,$checkbal){
			$dbh=$this->connect();

			//Check If Username Already Exist
			$queryC=$dbh->prepare("SELECT planid FROM datapins WHERE planid=:p");
			$queryC->bindParam(':p',$planid,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New User
			$sql="INSERT INTO  datapins (datanetwork,name,type,planid,day,price,userprice,agentprice,vendorprice,loadpin,checkbalance) 
			VALUES(:n,:d,:dt,:p,:du,:pr,:up,:ap,:vp,:lp,:cb)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':dt',$datatype,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
            $query->bindParam(':lp',$loadpin,PDO::PARAM_STR);
            $query->bindParam(':cb',$checkbal,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}


		//Update Data Pin
		public function updateDataPin($pin,$network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice,$loadpin,$checkbal){
			$dbh=$this->connect();

			$sql="UPDATE datapins SET datanetwork=:n,name=:d,type=:dt,planid=:p,day=:du,price=:pr,userprice=:up,agentprice=:ap,vendorprice=:vp,loadpin=:lp,checkbalance=:cb WHERE dpId=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id',$pin,PDO::PARAM_STR);
            $query->bindParam(':n',$network,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':dt',$datatype,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
			$query->bindParam(':lp',$loadpin,PDO::PARAM_STR);
            $query->bindParam(':cb',$checkbal,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
            
		}

			///Delete A Data Plan
			public function deleteDataPin($id){
				$dbh=$this->connect();
				$sql = "DELETE FROM datapins WHERE dpId=$id";
				$query = $dbh->prepare($sql);
				$query->execute();
				return 0;
			}

		//----------------------------------------------------------------------------------------------------------------
		//	Cable Plan Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Cable Provider
		public function getCableProvider(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM cableid ORDER BY cableid ASC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get Cable Plans
		public function getCablePlans(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM cableplans a, cableid b WHERE a.cableprovider=b.cableid";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}


		//Add Cable Plans
		public function addCablePlan($provider,$planname,$planid,$duration,$price,$userprice,$agentprice,$vendorprice){
			$dbh=$this->connect();

			//Check If Username Already Exist
			$queryC=$dbh->prepare("SELECT planid FROM cableplans WHERE planid=:p");
			$queryC->bindParam(':p',$planid,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create New User
			$sql="INSERT INTO cableplans (cableprovider,name,planid,day,price,userprice,agentprice,vendorprice) 
			VALUES(:cp,:n,:p,:du,:pr,:up,:ap,:vp)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':cp',$provider,PDO::PARAM_STR);
            $query->bindParam(':n',$planname,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}


		//Update Cable Plan
		public function updateCablePlan($plan,$provider,$planname,$planid,$duration,$price,$userprice,$agentprice,$vendorprice){
			$dbh=$this->connect();

			//If Not Exist, Create New User
			$sql="UPDATE cableplans SET cableprovider=:p,name=:pn,planid=:pi,day=:du,price=:pr,userprice=:up,agentprice=:ap,vendorprice=:vp WHERE cpId=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id',$plan,PDO::PARAM_STR);
            $query->bindParam(':p',$provider,PDO::PARAM_STR);
            $query->bindParam(':pn',$planname,PDO::PARAM_STR);
            $query->bindParam(':pi',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            $query->bindParam(':up',$userprice,PDO::PARAM_STR);
            $query->bindParam(':ap',$agentprice,PDO::PARAM_STR);
            $query->bindParam(':vp',$vendorprice,PDO::PARAM_STR);
            if($query->execute()){return 0; } else {return 1; }
            
		}

		///Delete A Cable Plan
		public function deleteCablePlan($id){
			$dbh=$this->connect();
			$sql = "DELETE FROM cableplans WHERE cpId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}


		//----------------------------------------------------------------------------------------------------------------
		// Subscribers
		//----------------------------------------------------------------------------------------------------------------

		//Get Subscribers
		public function getSubscribers($limit){
			$dbh=$this->connect();
			$sql = "SELECT * FROM subscribers ORDER BY sId DESC LIMIT $limit,1000";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $results;} else{return 1;}
		}

		public function resetAccountApiKey($id){
			$dbh=$this->connect();
			$id = base64_decode($id);
			$id = (float) $id;
			$apiKey = substr(str_shuffle("0123456789ABCDEFGHIJklmnopqrstvwxyzAbAcAdAeAfAgAhBaBbBcBdC1C23C3C4C5C6C7C8C9xix2x3"), 0, 60).time();
				
			$sql = "UPDATE subscribers SET sApiKey=:api WHERE sId = $id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':api',$apiKey,PDO::PARAM_STR);
            if($query->execute()){return 0;} else {return 1;}
		}

		//Delete User Account
		public function terminateUserAccount($id){
			$id=(float) base64_decode($id);
			$dbh=$this->connect();

			//Delete All Transactions
			$sql="DELETE FROM transactions WHERE sId=$id ";
            $query = $dbh->prepare($sql);
            $query->execute();

			//Delete All Transactions
			$sql2="DELETE FROM userlogin WHERE user=$id ";
            $query2 = $dbh->prepare($sql2);
            $query2->execute();


			//Delete All Transactions
			$sql3="DELETE FROM uservisits WHERE user=$id ";
            $query3 = $dbh->prepare($sql3);
            $query3->execute();

			//Delete Account Messages
			$sql4="DELETE FROM contact WHERE sId=$id ";
            $query4 = $dbh->prepare($sql4);
            $query4->execute();

			//Delete Account
			$sql5="DELETE FROM subscribers WHERE sId=$id ";
            $query5 = $dbh->prepare($sql5);
            $query5->execute();

            return 0;
		}

		//Get Subscribers
		public function getSubscribersDetails($id){
			$dbh=$this->connect();
			
			$sql = "SELECT * FROM subscribers WHERE sId = :id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id',$id,PDO::PARAM_INT);
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
           return $results;
		}

		public function updateSubscriber($id,$email,$phone,$accounttype,$accountstatus){
			$dbh=$this->connect();
			$id = base64_decode($id);
			$id = (float) $id;
			$accounttype = (float) $accounttype;
			$accountstatus = (float) $accountstatus;
			$sql = "UPDATE subscribers SET sType = $accounttype, sRegStatus= $accountstatus, sEmail=:e, sPhone=:p WHERE sId = $id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':e',$email,PDO::PARAM_STR);
            $query->bindParam(':p',$phone,PDO::PARAM_STR);
            if($query->execute()){return 0;} else {return 1;}
		}

		public function updateSubscriberPass($id,$pass){
			$dbh=$this->connect();
			$id = base64_decode($id);
			$id = (float) $id;
			$hash=substr(sha1(md5($pass)), 3, 10);
			$sql = "UPDATE subscribers SET sPass=:pass WHERE sId = $id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pass',$hash,PDO::PARAM_STR);
            if($query->execute()){return 0;} else {return 1;}
		}


		//----------------------------------------------------------------------------------------------------------------
		// Exam Pin Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Exam pin Setting
		public function getExamPinDetails($exam){
			$dbh=$this->connect();
			$sql = "SELECT * FROM examid WHERE provider=:exam";
            $query = $dbh->prepare($sql);
			$query->bindParam(':exam',$exam,PDO::PARAM_STR); 
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            return $results;
		}

		//Update Exam pin Setting
		public function updateExamPin($exam,$examid,$examprice,$buying_price,$examstatus){
			$dbh=$this->connect();
			$id = (int) $exam;
			
			$sql="UPDATE examid SET examid=:e, price=:g, buying_price=:l, providerStatus=:a WHERE eId = $id";
			$query = $dbh->prepare($sql); 
			$query->bindParam(':e',$examid,PDO::PARAM_INT); 
			$query->bindParam(':g',$examprice,PDO::PARAM_INT); 
			$query->bindParam(':l',$buying_price,PDO::PARAM_INT);
			$query->bindParam(':a',$examstatus,PDO::PARAM_STR); 
			
			$query->execute();

			return 0;
		}


		//----------------------------------------------------------------------------------------------------------------
		// Electricity Pin Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Electricity Bill Setting
		public function getElectricityBillDetails($electricity){
			$dbh=$this->connect();
			$sql = "SELECT * FROM electricityid WHERE abbreviation=:electricity";
            $query = $dbh->prepare($sql);
			$query->bindParam(':electricity',$electricity,PDO::PARAM_STR); 
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            return $results;
		}

		public function updateElectricityBill($electricity,$electricityid,$electricitystatus){
			$dbh=$this->connect();
			$id = (int) $electricity;

			$sql="UPDATE electricityid SET electricityid=:e, providerStatus=:p WHERE eId = $id";
			$query = $dbh->prepare($sql); 
			$query->bindParam(':e',$electricityid,PDO::PARAM_STR); 
			$query->bindParam(':p',$electricitystatus,PDO::PARAM_STR); 
			
			$query->execute();
			return 0;

		}

		//----------------------------------------------------------------------------------------------------------------
		// Wallet Management
		//----------------------------------------------------------------------------------------------------------------

		//Credit Debit User
		public function creditDebitUser($email,$action,$amount,$reason,$ref){
			$dbh=$this->connect();
			$sql = "SELECT * FROM subscribers WHERE sEmail = :e";
            $query = $dbh->prepare($sql);
			$query->bindParam(':e',$email,PDO::PARAM_STR);
            $query->execute();
            $results=$query->fetch(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){
				
				$amount = (float) $amount;
				$oldbalance = (float) $results->sWallet;
				$userId = $results->sId;
				$fname = $results->sFname;
				
				if($amount > $oldbalance && $action == "Debit"){return 2;}
				else{

					if($action == "Credit"){$newbalance = $oldbalance + $amount;}
					elseif($action == "Debit"){$newbalance = $oldbalance - $amount;}
					else{return 3;}

					$servicename = "Wallet {$action}";
    				$servicedesc = "Wallet {$action} of N{$amount} for user {$email}. Reason: {$reason}";
					$message = "Operation Successful. Account {$action}ed with N{$amount}. <br/> Old Balance Is: N".number_format($oldbalance)." <br/> New Balance Is: N".number_format($newbalance).".";
					$status = 0;
					$date=date("Y-m-d H:i:s");

					//Record Transaction
					$sql2 = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
					$query2 = $dbh->prepare($sql2);
					$query2->bindParam(':user',$userId,PDO::PARAM_INT);
					$query2->bindParam(':ref',$ref,PDO::PARAM_STR);
					$query2->bindParam(':sn',$servicename,PDO::PARAM_STR);
					$query2->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
					$query2->bindParam(':a',$amount,PDO::PARAM_STR);
					$query2->bindParam(':s',$status,PDO::PARAM_INT);
					$query2->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
					$query2->bindParam(':nb',$newbalance,PDO::PARAM_STR);
					$query2->bindParam(':d',$date,PDO::PARAM_STR);
					$query2->execute();

					$lastInsertId = $dbh->lastInsertId();
					if($lastInsertId){
						$response = array();
						//Update Account Type & Balance
						$sql3 = "UPDATE subscribers SET sWallet=:bal WHERE sId=:id";
						$query3 = $dbh->prepare($sql3);
						$query3->bindParam(':id',$userId,PDO::PARAM_INT);
						$query3->bindParam(':bal',$newbalance,PDO::PARAM_STR);
						if($query3->execute()){$response["status"]="success"; $response["msg"]=$message;}
						else{$response["status"]="fail"; $response["msg"]="Could Not Update Balance.";}

						//Send Email Notification
						$subject = $servicename ." (".$this->sitename.")";
						$message = "Hi ".$fname.", This is to notify you that your account have been {$action}ed with N{$amount}. <br/>";
						$message .="<h3>Old Balance Is: N".number_format($oldbalance)." <br/> New Balance Is: N".number_format($newbalance).".</h3>";
						self::sendMail($email,$subject,$message);

						return $response;
					}
				}
			} 
			else{return 1;}
		}

		
		//----------------------------------------------------------------------------------------------------------------
		//	Transactions Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get All Transactions
		public function getTransactions($limit){
			$dbh=$this->connect();
			$addon="";
			
			if(isset($_GET["search"])){
    			
				$search=(isset($_GET["search"])) ? $_GET["search"] : "";  
				$searchfor = (isset($_GET["searchfor"])) ? $_GET["searchfor"] : ""; 

    			if($search == ""){
        			if($searchfor == "all"){$addon="";}
        			if($searchfor == "user"){$addon="";}
        			if($searchfor == "wallet"){$addon=" AND b.servicename ='Wallet Credit' ";}
        			if($searchfor == "monnify"){$addon=" AND b.transref LIKE '%MNFY%' ";}
        			if($searchfor == "paystack"){$addon=" AND b.servicedesc LIKE '%Paystack%' ";}
        			if($searchfor == "airtime"){$addon=" AND b.servicename LIKE '%Airtime%' ";}
        			if($searchfor == "data"){$addon=" AND b.servicename LIKE '%Data%' ";}
        			if($searchfor == "cable"){$addon=" AND b.servicename LIKE '%Cable%' ";}
        			if($searchfor == "electricity"){$addon=" AND b.servicename LIKE '%Electricity%' ";}
        			if($searchfor == "exam"){$addon=" AND b.servicename LIKE '%Exam%' ";}
        			if($searchfor == "reference"){$addon=" AND b.transref LIKE :search ";}
    			}
    			else{
        			
        			if($searchfor == "all"){$addon=" AND b.servicedesc LIKE :search";}
        			if($searchfor == "user"){$addon=" AND (a.sPhone LIKE :search OR a.sEmail LIKE :search) ";}
        			if($searchfor == "wallet"){$addon=" AND (a.sPhone LIKE :search AND b.servicename ='Wallet Credit') ";}
        			if($searchfor == "monnify"){$addon=" AND ((a.sPhone LIKE :search OR a.sEmail LIKE :search) AND b.transref LIKE '%MNFY%') ";}
        			if($searchfor == "paystack"){$addon=" AND ((a.sPhone LIKE :search OR a.sEmail LIKE :search) AND b.servicedesc LIKE '%Paystack%') ";}
					if($searchfor == "airtime"){$addon=" AND (a.sPhone LIKE :search OR b.servicdesc LIKE :search) AND b.servicename LIKE '%Airtime%') ";}
        			if($searchfor == "data"){$addon=" AND ((a.sPhone LIKE :search OR b.servicdesc LIKE :search) AND b.servicename LIKE '%Data%') ";}
        			if($searchfor == "cable"){$addon=" AND ((a.sPhone LIKE :search OR b.servicdesc LIKE :search) AND b.servicename LIKE '%Cable%') ";}
        			if($searchfor == "electricity"){$addon=" AND ((a.sPhone LIKE :search OR b.servicdesc LIKE :search) AND b.servicename LIKE '%Electricity%') ";}
        			if($searchfor == "exam"){$addon=" AND ((a.sPhone LIKE :search OR b.servicdesc LIKE :search) AND b.servicename LIKE '%Exam%') ";}
        			if($searchfor == "reference"){$addon=" AND b.transref LIKE :search ";}
    			}
			}
			
			$sql = "SELECT a.sFname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql.=$addon." ORDER BY b.date DESC LIMIT $limit, 1000";
            $query = $dbh->prepare($sql);
            if(isset($_GET["search"])): if($search <> ""): $query->bindValue(':search','%'.$search.'%'); endif; endif;
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get All Processing Transactions
		public function getProcessingTransactions($limit){
			$dbh=$this->connect();
			
			$sql = "SELECT a.sFname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql.=" AND b.status=5 ORDER BY b.date DESC LIMIT $limit, 1000";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}

		//Get All Manual Transactions
		public function getManualTransactions($limit){
			$dbh=$this->connect();
			
			$sql = "SELECT a.sFname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql.=" AND b.status=5 AND (b.servicedesc LIKE '%(Manual)%' OR b.servicedesc LIKE '%(MM)%') ORDER BY b.date DESC LIMIT $limit, 1000";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}


		//Get Transaction Details
		public function getTransactionDetails($ref){
			$dbh=$this->connect();
			$sql = "SELECT a.sFname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId AND transref=:ref";
            $query = $dbh->prepare($sql);
			$query->bindParam(':ref',$ref,PDO::PARAM_STR);
            $query->execute();
            $result=$query->fetch(PDO::FETCH_OBJ);
            return $result;
		}
		
		//Update Transaction Details
		public function updateTransactionStatus($user,$trans,$transstatus,$amount,$disc){
			$dbh=$this->connect();
			
			$transstatus = (int) $transstatus;
			$trans = base64_decode($trans);
			$user = base64_decode($user);
			$amount = base64_decode($amount);
			$disc = base64_decode($disc);

			$theStatus = $transstatus;

			if($transstatus == 10){$theStatus = 0;}
			if($transstatus == 11){$theStatus = 1;}

			$sqlD = "UPDATE transactions SET status=:status WHERE transref=:ref";
			$queryD = $dbh->prepare($sqlD);
			$queryD->bindParam(':status',$theStatus,PDO::PARAM_INT);
			$queryD->bindParam(':ref',$trans,PDO::PARAM_STR);
			$queryD->execute();


			//11 Fail And Refund --- 10 Success And Debit
			if($transstatus == 11 || $transstatus == 10){
				$sqlW = "SELECT sWallet FROM subscribers WHERE sId=$user";
                $queryW = $dbh->prepare($sqlW);
                $queryW->execute();
                $resultW=$queryW->fetch(PDO::FETCH_OBJ);
                $oldbalance = (float) $resultW->sWallet;
                
				if($transstatus == 10){$newbalance = $oldbalance - $amount;}
                if($transstatus == 11){$newbalance = $oldbalance + $amount;}
                

                $sqlS = "UPDATE subscribers SET sWallet=:nb WHERE sId=:id";
                $queryS = $dbh->prepare($sqlS);
                $queryS->bindParam(':id',$user,PDO::PARAM_INT);
                $queryS->bindParam(':nb',$newbalance,PDO::PARAM_STR);
                $queryS->execute();

				//Record Transaction
				if($transstatus == 10){
					$servicename = "Debit";
					$servicedesc = "Debit of N{$amount} for {$disc} tansaction reference {$trans}.";
					$status = 0;
					$date=date("Y-m-d H:i:s");
					$ref = "DEBIT/".$trans."/".time();
				}

				if($transstatus == 11){
					$servicename = "Refund";
					$servicedesc = "Refund of N{$amount} for {$disc} tansaction reference {$trans}.";
					$status = 0;
					$date=date("Y-m-d H:i:s");
					$ref = "REFUND/".$trans."/".time();
				}
				

				$sql = "INSERT INTO transactions SET sId=:user,transref=:ref,servicename=:sn,servicedesc=:sd,amount=:a,status=:s,oldbal=:ob,newbal=:nb,date=:d";
				$query = $dbh->prepare($sql);
				$query->bindParam(':user',$user,PDO::PARAM_INT);
				$query->bindParam(':ref',$ref,PDO::PARAM_STR);
				$query->bindParam(':sn',$servicename,PDO::PARAM_STR);
				$query->bindParam(':sd',$servicedesc,PDO::PARAM_STR);
				$query->bindParam(':a',$amount,PDO::PARAM_STR);
				$query->bindParam(':s',$status,PDO::PARAM_INT);
				$query->bindParam(':ob',$oldbalance,PDO::PARAM_STR);
				$query->bindParam(':nb',$newbalance,PDO::PARAM_STR);
				$query->bindParam(':d',$date,PDO::PARAM_STR);
				$query->execute();

				return 0;
			}
			
		}

		//----------------------------------------------------------------------------------------------------------------
		//	Sale Transactions Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get All Transactions
		public function getSaleTransactions($service,$datefrom,$dateto){
			$dbh=$this->connect();
			$addon="";
			
			if($service <> "All"){
    			if($service == "Airtime"){$addon=" servicename = 'Airtime' AND ";}
        		if($service == "Data"){$addon=" servicename = 'Data' AND ";}
			}
			
			//Get Transactions
			$sql = "SELECT * FROM transactions WHERE ";
			$sql.= $addon." (date BETWEEN '$datefrom' AND '$dateto') ORDER BY servicename DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
			
		}

		//Get All Transactions
		/*public function getSaleTransactions($service,$datefrom,$dateto){
			$dbh=$this->connect();
			
			//Filter All Service
			$addon=" AND ((b.servicename='Cable TV' OR b.servicename='Data Pin') ";
			$addon.=" OR (b.servicename='Airtime' OR b.servicename='Data') ";
			$addon.=" OR (b.servicename='Electricity Bill' OR b.servicename='Exam Pin'))";
			
			$addon="";
			//Get Specific Service
			if($service <> "All"){
    			if($service == "Airtime"){$addon=" AND b.servicename = 'Airtime' ";}
        		if($service == "Data"){$addon=" AND b.servicename = 'Data' ";}
			}
			
			//Get Transactions
			//$sql = "SELECT a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql = "SELECT * FROM transactions WHERE tId>0 ";
			$sql.= $addon." AND (date BETWEEN '$datefrom' AND '$dateto') ORDER BY servicename DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}*/


		

		

		//----------------------------------------------------------------------------------------------------------------
		// Contact Messages
		//----------------------------------------------------------------------------------------------------------------

		//Get Contact Messages
		public function getContact(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM contact ORDER BY dPosted DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0){return $results;} else{return 1;}
		}

		//Get Contact
		public function deleteContact($id){
			$dbh=$this->connect();
			$sql = "DELETE FROM contact WHERE msgId=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}
		

		//----------------------------------------------------------------------------------------------------------------
		// Dashboard
		//----------------------------------------------------------------------------------------------------------------
 

		//Get General Site Statictics
		public function getGeneralSiteReports(){
			$dbh=$this->connect();
			
			$today = strtotime(date("Y-m-d") . '00:00:00');
			$last = strtotime(date("Y-m-d") . '23:59:59');

			$sql1 ="SELECT COUNT(sId) AS sCount FROM subscribers WHERE sType = 1";
			$sql2 ="SELECT COUNT(sId) AS aCount FROM subscribers WHERE sType = 2";
		  	$sql3 ="SELECT COUNT(tId) AS tCount FROM transactions";
		  	$sql4 ="SELECT SUM(sWallet) AS uwCount FROM subscribers WHERE sType = 1";
		  	$sql5 ="SELECT SUM(sWallet) AS awCount FROM subscribers WHERE sType = 2";
		  	$sql6 ="SELECT COUNT(msgId) AS mCount  FROM contact";
		  	$sql7 ="SELECT COUNT(id) AS visitCount  FROM uservisits WHERE visitTime BETWEEN $today AND $last";
		  	$sql8 ="SELECT a.sFname,a.sPhone,a.sType,a.sEmail,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ORDER BY b.date DESC LIMIT 50";
		  	//$sql9 ="SELECT dAcc,vAcc FROM apiconfigs WHERE aId=1";
			$sql10 ="SELECT SUM(sWallet) AS vwCount FROM subscribers WHERE sType = 3";
			$sql11 ="SELECT COUNT(sId) AS vCount FROM subscribers WHERE sType = 3";
			$sql12 ="SELECT COUNT(sId) AS rCount FROM subscribers WHERE sReferal <> '' ";
			$sql13 ="SELECT SUM(sRefWallet) AS rwCount FROM subscribers";
			$sql14 ="SELECT COUNT(tId) AS alphaCount FROM transactions WHERE status=2 AND servicename <> 'Smile'";
			$sql15 ="SELECT COUNT(tId) AS smileCount FROM transactions WHERE status=2 AND servicename = 'Smile'";
		  	
		  	
             
		  	$query1 = $dbh -> prepare($sql1);
		  	$query2 = $dbh -> prepare($sql2);
		  	$query3 = $dbh -> prepare($sql3);
		  	$query4 = $dbh -> prepare($sql4);
		  	$query5 = $dbh -> prepare($sql5);
		  	$query6 = $dbh -> prepare($sql6);
		  	$query7 = $dbh -> prepare($sql7);
		  	$query8 = $dbh -> prepare($sql8);
		  	//$query9 = $dbh -> prepare($sql9);
		  	$query10 = $dbh -> prepare($sql10);
		  	$query11 = $dbh -> prepare($sql11);
		  	$query12 = $dbh -> prepare($sql12);
		  	$query13 = $dbh -> prepare($sql13);
		  	$query14 = $dbh -> prepare($sql14);
		  	$query15 = $dbh -> prepare($sql15);
		  	
		 
		  	$query1->execute();
		  	$query2->execute();
		  	$query3->execute();
		  	$query4->execute();
		  	$query5->execute();
		  	$query6->execute();
		  	$query7->execute();
		  	$query8->execute();
		  	//$query9->execute();
		  	$query10->execute();
		  	$query11->execute();
		  	$query12->execute();
		  	$query13->execute();
		  	$query14->execute();
		  	$query15->execute();
		  	
		  	$results1=$query1->fetch(PDO::FETCH_OBJ);
		  	$results2=$query2->fetch(PDO::FETCH_OBJ);
		  	$results3=$query3->fetch(PDO::FETCH_OBJ);
		  	$results4=$query4->fetch(PDO::FETCH_OBJ);
		  	$results5=$query5->fetch(PDO::FETCH_OBJ);
		  	$results6=$query6->fetch(PDO::FETCH_OBJ);
		  	$results7=$query7->fetch(PDO::FETCH_OBJ);
		  	$results8=$query8->fetchAll(PDO::FETCH_OBJ);
		  	//$results9=$query9->fetch(PDO::FETCH_OBJ);
		  	$results10=$query10->fetch(PDO::FETCH_OBJ);
		  	$results11=$query11->fetch(PDO::FETCH_OBJ);
		  	$results12=$query12->fetch(PDO::FETCH_OBJ);
		  	$results13=$query13->fetch(PDO::FETCH_OBJ);
		  	$results14=$query14->fetch(PDO::FETCH_OBJ);
		  	$results15=$query15->fetch(PDO::FETCH_OBJ);
		 
		  
		  	$data=array();
		  	$data["sCount"]=$results1->sCount;
		  	$data["aCount"]=$results2->aCount;
		  	$data["tCount"]=$results3->tCount;
		  	$data["uwCount"]=$results4->uwCount;
		  	$data["awCount"]=$results5->awCount;
		  	$data["mCount"]=$results6->mCount;
		  	$data["visitCount"]=$results7->visitCount;
		  	$data["transactions"]=$results8;

			//Wallet Balance  
		  	//$data["dataaccount"]=$results9->dAcc;
			//$data["vtuaccount"]=$results9->vAcc;
			$data["vwCount"]=$results10->vwCount;
			$data["vCount"]=$results11->vCount;
			$data["rCount"]=$results12->rCount;
			$data["rwCount"]=$results13->rwCount;
			
			$data["alphaCount"]=$results14->alphaCount;

			$uwCount = (float) $results4->uwCount;
			$awCount = (float) $results5->awCount;
			$rwCount = (float) $results13->rwCount;
			$vwCount = (float) $results10->vwCount;
			  
			$data["userWalletTotal"] = number_format($uwCount + $awCount + $rwCount + $vwCount,2);

			$data["alphaCount"]=$results14->alphaCount;
			$data["smileCount"]=$results15->smileCount;
		  	
		  	return $data;
		}


		//----------------------------------------------------------------------------------------------------------------
		//	Airtime To Cash Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get Pending Transactions
		public function getPendingAirtimeToCash(){
			$dbh=$this->connect();
			$sql = "SELECT a.sId,a.sFname,a.sLname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql.=" AND b.servicename='Airtime To Cash' AND b.status=5 ORDER BY b.date DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Get All Transactions
		public function getAllAirtimeToCash($limit){
			$dbh=$this->connect();
			$sql = "SELECT a.sFname,a.sLname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, transactions b WHERE a.sId=b.sId ";
			$sql.=" AND b.servicename='Airtime To Cash' ORDER BY b.date DESC LIMIT $limit, 1000";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		//Update Airtime To Cash Status
		public function updateAirtimeToCashStatus($id,$status){
			$dbh=$this->connect();
			
			$status = (int) $status;
			$id = (int) $id;
			
			// If Status Is 0 Update & Credit User Else Update Status Only
			$transStatus = ($status == 2) ? 0 : $status;
			
			//Update Transaction Status
			$sql = "UPDATE transactions SET status =  $transStatus WHERE tId= $id";
			$query = $dbh->prepare($sql);
            $query->execute();
            
            //Get Transaction Details
            $sql2 = "SELECT * FROM transactions WHERE tId= $id";
    		$query2= $dbh->prepare($sql2);
            $query2->execute();
            $result2=$query2->fetch(PDO::FETCH_OBJ);
            
            //If Status Is Approve, Credit User Wallet & Record Transaction
            if($status == 0){
                $ref="AIRTIME_SWAP_".rand(1000,9999).time();
                $sql3 = "SELECT sEmail FROM subscribers WHERE sId=$result2->sId";
    			$query3= $dbh->prepare($sql3);
                $query3->execute();
                $result3=$query3->fetch(PDO::FETCH_OBJ);
                $this->creditDebitUser($result3->sEmail,"Credit",$result2->amount,"Payment For Airtime To Cash",$ref);
            }
            
		}
		
		//----------------------------------------------------------------------------------------------------------------
		// Manual Fund Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get Pending Transactions
		public function getPendingManualFund(){
			$dbh=$this->connect();
			$sql = "SELECT a.sId,a.sFname,a.sLname,a.sPhone,a.sEmail,a.sType,b.* FROM subscribers a, manualfunds b WHERE a.sId=b.sId AND b.status='pending' ORDER BY b.dPosted DESC";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
		
		
		//Update Manual Fund Status
		public function updateManualFundStatus($id,$email,$amount,$status){
			$dbh=$this->connect();
			
			$id = (int) $id;
			
			if($status == "success"){
			    $action = "Credit";
			    $reason = "Manual Funding";
			    $ref="CREDIT_".time().rand(1000,9999);
			    $this->creditDebitUser($email,$action,$amount,$reason,$ref);
			}
			
			//Update Transaction Status
			$sql = "UPDATE manualfunds SET status=:sta WHERE tId = $id";
			$query = $dbh->prepare($sql);
			$query->bindParam(':sta',$status,PDO::PARAM_STR);
            $query->execute();
			
	        return 0;
            
		}
		
		
		//----------------------------------------------------------------------------------------------------------------
		//	SMILE Data Plan Management
		//----------------------------------------------------------------------------------------------------------------
		

		//Get SMILE Data Plans
		public function getSmileDataPlans(){
			$dbh=$this->connect();
			$sql = "SELECT * FROM smiledata";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            return $results;
		}
		
			//Update SMILE Data
		public function updateSmileDataPlan($plan,$dataname,$planid,$duration,$price){
			$dbh=$this->connect();

			
			//If Not Exist, Create New User
			$sql="UPDATE smiledata SET description=:d,BundleTypeCode=:p,validity=:du,price=:pr WHERE id=:id";
            $query = $dbh->prepare($sql);
            $query->bindParam(':id',$plan,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':p',$planid,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->bindParam(':pr',$price,PDO::PARAM_STR);
            
            if($query->execute()){return 0; } else {return 1; }
            
		}


		//Add Data Plans
		public function addSmileDataPlan($planid,$dataname,$price, $duration){
			$dbh=$this->connect();

			//Check If smile planid Already Exist
			$queryC=$dbh->prepare("SELECT BundleTypeCode FROM smiledata WHERE BundleTypeCode=:p");
			$queryC->bindParam(':p',$planid,PDO::PARAM_STR);
			$queryC->execute();
			if($queryC->rowCount() > 0){return 2;}
			
			//If Not Exist, Create add new plan
			$sql="INSERT INTO  smiledata (BundleTypeCode, description, price, validity) 
			VALUES(:n,:d,:p,:du)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':n',$planid,PDO::PARAM_STR);
            $query->bindParam(':d',$dataname,PDO::PARAM_STR);
            $query->bindParam(':p',$price,PDO::PARAM_STR);
            $query->bindParam(':du',$duration,PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if($lastInsertId){return 0;} else{return 1;}
		}

		///Delete Smile  Data Plan
		public function deleteSmileDataPlan($id){
			$dbh=$this->connect();
			$sql = "DELETE FROM smiledata WHERE id=$id";
            $query = $dbh->prepare($sql);
            $query->execute();
            return 0;
		}

		


	}

?>