<?php
	
	class AdminController extends Controller{

		public static $role;
		public static $adminName;
		public static $adminUsername;
		public static $sysId;
		protected $model;

		//Default Constructor
		public function __construct(){
			if(isset($_SESSION['sysId']) && isset($_SESSION["sysRole"])){
				if($_SESSION['sysId']!='' && $_SESSION["sysRole"] !=''){
					self::$role=(float) $_SESSION["sysRole"];
					self::$adminName=$_SESSION["sysName"];
					self::$adminUsername=$_SESSION["sysUser"];
				    self::$sysId=$_SESSION['sysId'];
					
					$this->model=new AdminModel;
				}
				else{ header("Location: ../"); exit();} 
			}
			else{ header("Location: ../"); exit();}
		}

		//----------------------------------------------------------------------------------------------------------------
		// System Users Account Management
		//----------------------------------------------------------------------------------------------------------------

		//Logout Users From System
		public function logoutUser(){
			session_start();
			session_destroy();
			header("Location: ../");
			exit();
		}

		//Create Account For New System Users
		public function createAccount(){
			extract($_POST);
			$check=$this->model->createAccount($name,$username,$password,$role);
			if($check == 0){return $this->createNotification1("alert-success","New User Created Successfully.");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Username Already Exist, Please Try Again.");}
			else{return $this->createNotification1("alert-danger","Unable To Create User, Please Try Again.");}
		}

		//Create Account For Subscriber
		public function createSubscriberAccount(){
			extract($_POST);
			$obj = new Account;
			$check=$obj->registerUser($fname,$lname,$email,$phone,$password,$state,1,"admin","1234");
			if($check == 0){return $this->createNotification1("alert-success","New Subscriber Account Created Successfully.");}
			elseif($check == 1){return $this->createNotification1("alert-danger","Email & Phone Number Already Exist.");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Email Already Exist.");}
			elseif($check == 3){return $this->createNotification1("alert-danger","Phone Number Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Create Subscriber Account, Please Try Again.");}
		}

		//Manage Account Of System Users
		public function getAccounts(){
			$data=$this->model->getAccounts();
			return $data;
		}
 
		//Get Account By ID
		public function getAccountById($id){
			$data=$this->model->getAccountById($id);
			return $data;
		} 

		public function getMyAdminDetails(){
			$data=$this->model->getAccountById(self::$sysId);
			return $data;
		} 

		//Update Account
		public function updateAccountStatus(){
			extract($_POST);
			$check=$this->model->updateAccountStatus($id,$status);
			if($check == 0){return 0; } else{return 1; }
		}

		//Update Account
		public function updateAdminAccount(){
			extract($_POST);
			$check=$this->model->updateAdminAccount(self::$sysId,$name,$password,$newpassword);
			if($check == 0){
				self::$adminName=$name;
				return $this->createNotification1("alert-info","Account Updated Successfully");
			}
			elseif($check == 1){return $this->createNotification1("alert-danger","Wrong Password, Please Try Again");}
			else{return $this->createNotification1("alert-danger","Unable To Update Account, Please Try Again");}
		}


		//Update Account Status
		public function updateAdminAccountStatus(){
			extract($_POST);
			if(!empty($newpin)){if(strlen($newpin) <> 4){return $this->createNotification1("alert-danger","Pin Should Be Four Digit");}}
			$check=$this->model->updateAdminAccountStatus(self::$sysId,$pinstatus,$loginpin,$newpin);
			if($check == 0){
				return $this->createNotification1("alert-info","Account Pin Updated Successfully");
			}
			elseif($check == 1){return $this->createNotification1("alert-danger","Wrong Pin, Please Try Again");}
			else{return $this->createNotification1("alert-danger","Unable To Update Account Pin, Please Try Again");}
		}

		//----------------------------------------------------------------------------------------------------------------
		// Subscribers
		//----------------------------------------------------------------------------------------------------------------
		//Get All Subscribers
		public function getSubscribers($limit){
			$data=$this->model->getSubscribers($limit);
			return $data;
		}
 
		public function getSubscribersDetails($id){
			$data=$this->model->getSubscribersDetails($id);
			return $data;
		}

		public function updateSubscriber(){
			extract($_POST);
			$check=$this->model->updateSubscriber($user,$email,$phone,$accounttype,$accountstatus);
			if($check == 0){return $this->createNotification1("alert-success","Account Updated Successfully");}
			elseif($check == 1){return $this->createNotification1("alert-danger","Unable To Update Account, Please Try Again");}
			else{return $this->createNotification1("alert-danger","Unable To Update Account, Please Try Again");}
		}

		public function updateSubscriberPass(){
			extract($_POST);
			$check=$this->model->updateSubscriberPass($user,$paccess);
			if($check == 0){return $this->createNotification1("alert-success","Account Password Updated Successfully");}
			elseif($check == 1){return $this->createNotification1("alert-danger","Unable To Update Account Password, Please Try Again");}
			else{return $this->createNotification1("alert-danger","Unable To Update Account Password, Please Try Again");}
		}

		//Delete User Account
		public function terminateUserAccount(){
			extract($_POST);
			$check=$this->model->terminateUserAccount($id);
			if($check == 0){return 0; } else{return 1; }
		}

		//Change User Api Key
		public function resetAccountApiKey(){
			extract($_POST);
			$check=$this->model->resetAccountApiKey($id);
			if($check == 0){return 0; } else{return 1; }
		}

		//----------------------------------------------------------------------------------------------------------------
		// Exam Pin Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All exam
		public function getExamPinDetails(){
			$data=$this->model->getExamPinDetails($_GET["exam"]);
			return $data;
		}

		
		public function updateExamPin(){
			extract($_POST);
			$check=$this->model->updateExamPin($exam,$examid,$examprice,$buying_price,$examstatus);
			if($check == 0){return $this->createNotification1("alert-success","Exam pin Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Exam pin, Please Try Again");}
			
		}

		//----------------------------------------------------------------------------------------------------------------
		// Electricity Management
		//----------------------------------------------------------------------------------------------------------------

		//Get All electricity
		public function getElectricityBillDetails(){
			$data=$this->model->getElectricityBillDetails($_GET["electricity"]);
			return $data;
		}

		public function updateElectricityBill(){
			extract($_POST);
			$check=$this->model->updateElectricityBill($electricity,$electricityid,$electricitystatus);
			if($check == 0){return $this->createNotification1("alert-success","Electricity Bill Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Electricity Bill, Please Try Again");}
		}


		//----------------------------------------------------------------------------------------------------------------
		// Wallet Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Credit Debit User$email,$action,$amount,$reason
		public function creditDebitUser(){
			extract($_POST);
			$ref=$this->generateTransactionRef();
			$check=$this->model->creditDebitUser($email,$action,$amount,$reason,$ref);
			if(is_array($check)){
				if($check["status"] == "success"){
					return $this->createNotification1("alert-success",$check["msg"]);
				}
				else{
					return $this->createNotification1("alert-danger",$check["msg"]);
				}
			}
			elseif($check == 1){return $this->createNotification1("alert-danger","User Email Not Found");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Insufficent User Balance");}
			else{return $this->createNotification1("alert-danger","Unexpected Error, Please Try Again Later");}
			
		}

		//Generate Transaction Reference
		public function generateTransactionRef(){
			$tranId=rand(1000,9999).time();
			return $tranId;
		}

		
		//----------------------------------------------------------------------------------------------------------------
		// Site Settings
		//----------------------------------------------------------------------------------------------------------------
		
		public function getSiteSettings(){
			$data=$this->model->getSiteSettings();
			return $data;
		}

		
		public function updateNetworkSetting(){
			extract($_POST);
			$check=$this->model->updateNetworkSetting($network,$general,$vtuStatus,$sharesellStatus,$airtimepin,$datapin,$sme,$gifting,$share,$corporate,$networkid,$vtuId,$sharesellId,$smeId,$giftingId,$shareId,$corporateId);
			if($check == 0){return $this->createNotification1("alert-success","Network Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Network, Please Try Again");}
			
		}
 
		public function updateContactSetting(){
			extract($_POST);
			$check=$this->model->updateContactSetting($phone,$email,$whatsapp,$whatsappgroup,$instagram,$facebook,$twitter,$telegram);
			if($check == 0){return $this->createNotification1("alert-success","Contact Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Contact, Please Try Again");}
			
		}

		public function updateSiteSetting(){
			extract($_POST);
			$check=$this->model->updateSiteSetting($sitename,$siteurl,$apidocumentation,$referalupgradebonus,$referalairtimebonus,$referaldatabonus,$referalwalletbonus,$referalcablebonus,$referalexambonus,$referalmeterbonus,$wallettowalletcharges,$agentupgrade,$vendorupgrade,$accountname,$accountno,$bankname,$electricitycharges,$airtimemin,$airtimemax);
			if($check == 0){return $this->createNotification1("alert-success","Details Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Details, Please Try Again");}
			
		}

		public function updateSiteStyleSetting(){
			extract($_POST);
			$check=$this->model->updateSiteStyleSetting($sitecolor,$loginstyle,$homestyle);
			if($check == 0){return $this->createNotification1("alert-success","Details Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Details, Please Try Again");}
			
		}

		//----------------------------------------------------------------------------------------------------------------
		// SMILE Data Plan Management
		//----------------------------------------------------------------------------------------------------------------

		
		//Get SMILE Data Plan
		public function getSmileDataPlans(){
			$data=$this->model->getSmileDataPlans();
			return $data;
		}

		//Add SMILE Data Plan
		public function addSmileDataPlan(){
			extract($_POST);
			$check=$this->model->addSmileDataPlan($planid, $dataname,$price,$duration);
			if($check == 0){return $this->createNotification1("alert-success","Data Plan Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Plan With Same Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Plan, Please Try Again");}
		}

		//Update SMILE Data Plan
		public function updateSmileDataPlan(){
			extract($_POST);
			$check=$this->model->updateSmileDataPlan($plan,$dataname,$planid,$duration,$price);
			if($check == 0){return $this->createNotification1("alert-success","Plan Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Plan, Please Try Again");}
		}

		
		//Delete SMILE Data Plan
		public function deleteSmileDataPlan(){
			extract($_POST);
			$check=$this->model->deleteSmileDataPlan($id);
			if($check == 0){return 0;}
			else{return 1;}
		}


		//----------------------------------------------------------------------------------------------------------------
		// API Management
		//----------------------------------------------------------------------------------------------------------------
		
		
		public function getApiConfiguration(){
			$data=$this->model->getApiConfiguration();
			return $data;
		}

		public function getApiConfigurationLinks(){
			$data=$this->model->getApiConfigurationLinks();
			return $data;
		}

		public function updateApiConfiguration(){
			$check=$this->model->updateApiConfiguration();
			if($check == 0){return $this->createNotification1("alert-success","Details Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Details, Please Try Again");}
			
		}

		public function addNewApiDetails(){
			extract($_POST);
			$check=$this->model->addNewApiDetails($providername,$providerurl,$service,$code);
			if($check == 0){return $this->createNotification1("alert-success","Api Details For $providername $service Added Successfully");}
			elseif($check == 1){return $this->createNotification1("alert-danger","Invalid Access Code, Please Try Again");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Api Details Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Api, Please Try Again");}
		}

		

		//----------------------------------------------------------------------------------------------------------------
		// Email Management
		//----------------------------------------------------------------------------------------------------------------
		
		//----------------------------------------------------------------------------------------------------------------
		// Notification Management
		//----------------------------------------------------------------------------------------------------------------
		
		public function getNotificationStatus(){
			$data=$this->model->getNotificationStatus();
			return $data;
		}

		public function sendEmailToUser(){
			extract($_POST);
			$check=$this->model->sendEmailToUser($subject,$email,$message);
			if($check == 0){return $this->createNotification1("alert-success","Email Message Sent Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Send Mail, Please Try Again");}
			
		}


		
		public function updateNotificationStatus(){
			extract($_POST);
			$check=$this->model->updateNotificationStatus($notificationstatus);
			if($check == 0){return $this->createNotification1("alert-success","Network Status Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Network Status, Please Try Again");}
		}
		
		//Get Notification
		public function getNotifications(){
			$data=$this->model->getNotifications();
			return $data;
		}

		//Add Notification
		public function addNotification(){
			extract($_POST);
			$check=$this->model->addNotification($subject,$msgfor,$message);
			if($check == 0){return $this->createNotification1("alert-success","Notification Added Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Add Notification, Please Try Again");}
			
		}

		//Delete Notification
		public function deleteNotification(){
			extract($_POST);
			$check=$this->model->deleteNotification($id);
			if($check == 0){return 0;}
			else{return 1;}
		}
		
		

		//----------------------------------------------------------------------------------------------------------------
		// Airtime Discount Management
		//----------------------------------------------------------------------------------------------------------------

		//Get All Network
		public function getNetworks(){
			$data=$this->model->getNetworks();
			return $data;
		}

		//Get Airtime Discount
		public function getAirtimeDiscount(){
			$data=$this->model->getAirtimeDiscount();
			return $data;
		}

		//Add Airtime Discount
		public function addAirtimeDiscount(){
			extract($_POST);
			$check=$this->model->addAirtimeDiscount($network,$networktype,$buydiscount,$userdiscount,$agentdiscount,$vendordiscount);
			if($check == 0){return $this->createNotification1("alert-success","Discount Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Discount With Same Network Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Discount, Please Try Again");}
		}

		//Update Airtime Discount
		public function updateAirtimeDiscount(){
			extract($_POST);
			$check=$this->model->updateAirtimeDiscount($networkid,$network,$networktype,$buydiscount,$userdiscount,$agentdiscount,$vendordiscount);
			if($check == 0){return $this->createNotification1("alert-success","Discount Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Discount, Please Try Again");}
		}

		//----------------------------------------------------------------------------------------------------------------
		//Alpha Topup Management
		//----------------------------------------------------------------------------------------------------------------

		//Get Alpha Topup
		public function getAlphaTopup(){
			$data=$this->model->getAlphaTopup();
			return $data;
		}

		//Add Airtime Discount
		public function addAlphaTopup(){
			extract($_POST);
			$check=$this->model->addAlphaTopup($bprice,$sprice,$agent,$vendor);
			if($check == 0){return $this->createNotification1("alert-success","Alpha Topup Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Alpha Topup With Same value Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Alpha Topup, Please Try Again");}
		}

		//Update Alpha Topup
		public function updateAlphaTopup(){
			extract($_POST);
			$check=$this->model->updateAlphaTopup($alphaid,$buying,$selling,$agent,$vendor);
			if($check == 0){return $this->createNotification1("alert-success","Alpha Topup Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Alpha Topup, Please Try Again");}
		}

		//Delete Alpha Topup
		public function deleteAlphaTopup(){
			extract($_POST);
			$check=$this->model->deleteAlphaTopup($id);
			if($check == 0){return 0;}
			else{return 1;}
		}

		//Get All Pending Orders
		public function getPendingAlphaOrder(){
			$data=$this->model->getPendingAlphaOrder();
			return $data;
		}
		
		//Complete Alpha Topup Request
		public function completeAlphaTopupRequest(){
			extract($_POST);
			$check=$this->model->completeAlphaTopupRequest($id);
			if($check == 0){return 0;}
			else{return 1;}
		}

		//----------------------------------------------------------------------------------------------------------------
		// Recharge Card Pin Discount Management
		//----------------------------------------------------------------------------------------------------------------

		
		//Get Recharge Card Pin
		public function getRechargeCardPinDiscount(){
			$data=$this->model->getRechargeCardPinDiscount();
			return $data;
		}
		
		//Get Recharge Card Pins
		public function getAirtimePinStocks(){
			$data=$this->model->getAirtimePinStocks();
			return $data;
		}
		
		//Clear Used PIns
		public function clearUsedRechargeCardPins(){
			$data=$this->model->clearUsedRechargeCardPins();
			return $data;
		}
		
		//Upload Recharge Card Pins
		public function uploadRechargeCardPins(){
		    
		    extract($_POST);
		    
		    //Verify File Format
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/csv','text/plain','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            //Check If Uploaded File Is A Valid Excel File
            if(in_array($_FILES['pinfile']["type"],$allowedFileType)){
                $targetPath = '../../assets/documents/myspreadsheets/'.$_FILES['pinfile']['name'];
                if(move_uploaded_file($_FILES['pinfile']['tmp_name'], $targetPath)){
                    
                    //Initilize Spreadsheet Reader Class
                    $Reader = new SpreadsheetReader($targetPath);
                   	$check=$this->model->uploadRechargeCardPins($network,$amount,$pincolumn,$serialnocolumn,$Reader);
			        if($check == 0){return $this->createNotification1("alert-success","E-pins Uploaded Successfully");}
			        else{return $this->createNotification1("alert-danger","Unable To Save/Upload Pins, Please Try Again Later");}
                    
                }
                else{
                     return $this->createNotification1("alert-danger","Unable to upload file, please try again later.");
                }
            }
            else{
                return $this->createNotification1("alert-danger","Invalid File Format ({$_FILES['pinfile']["type"]}) Uploaded, Only xls,xlsx,ms-excel formats allowed, please upload a valid spreadsheet.");
            }

		
		}

		//Add ARecharge Card Pin
		public function addRechargeCardPinDiscount(){
			extract($_POST);
			$check=$this->model->addRechargeCardPinDiscount($network,$amount,$buyprice,$userdiscount,$agentdiscount,$vendordiscount,$loadpin,$checkbal,$planid);
			if($check == 0){return $this->createNotification1("alert-success","Discount Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Discount With Same Network Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Discount, Please Try Again");}
		}

		//Update Recharge Card Pin
		public function updateRechargeCardPinDiscount(){
			extract($_POST);
			$check=$this->model->updateRechargeCardPinDiscount($network,$amount,$buyprice,$userdiscount,$agentdiscount,$vendordiscount,$loadpin,$checkbal,$planid);
			if($check == 0){return $this->createNotification1("alert-success","Discount Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Discount, Please Try Again");}
		}

		public function getNumberOfAvailablePins(){
			$data=$this->model->getNumberOfAvailablePins();
			return $data;
		}

		//----------------------------------------------------------------------------------------------------------------
		// Data Pin Stock Management
		//----------------------------------------------------------------------------------------------------------------

		//Get Data Card Pins
		public function getDataPinStocks(){
			$data=$this->model->getDataPinStocks();
			return $data;
		}
		
		//Clear Used Data Pins
		public function clearUsedDataCardPins(){
			$data=$this->model->clearUsedDataCardPins();
			return $data;
		}
		 
		//Upload Data Card Pins
		public function uploadDataCardPins(){
		    
		    extract($_POST);
		    
		    //Verify File Format
            $allowedFileType = ['application/vnd.ms-excel','text/xls','text/csv','text/plain','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

            //Check If Uploaded File Is A Valid Excel File
            if(in_array($_FILES['pinfile']["type"],$allowedFileType)){
                $targetPath = '../../assets/documents/myspreadsheets/'.$_FILES['pinfile']['name'];
                if(move_uploaded_file($_FILES['pinfile']['tmp_name'], $targetPath)){
                    
                    //Initilize Spreadsheet Reader Class
                    $Reader = new SpreadsheetReader($targetPath);
                   	$check=$this->model->uploadDataCardPins($network,$amount,$pincolumn,$serialnocolumn,$Reader);
			        if($check == 0){return $this->createNotification1("alert-success","E-pins Uploaded Successfully");}
			        else{return $this->createNotification1("alert-danger","Unable To Save/Upload Pins, Please Try Again Later");}
                    
                }
                else{
                     return $this->createNotification1("alert-danger","Unable to upload file, please try again later.");
                }
            }
            else{
                return $this->createNotification1("alert-danger","Invalid File Format ({$_FILES['pinfile']["type"]}) Uploaded, Only xls,xlsx,ms-excel formats allowed, please upload a valid spreadsheet.");
            }

		
		}

		public function getNumberOfAvailableDataPins(){
			$data=$this->model->getNumberOfAvailableDataPins();
			return $data;
		}

		//----------------------------------------------------------------------------------------------------------------
		// Data Plan Management
		//----------------------------------------------------------------------------------------------------------------

		
		//Get Data Plan
		public function getDataPlans(){
			$data=$this->model->getDataPlans();
			return $data;
		}

			//Get Data Plan
		public function getDataPins(){
			$data=$this->model->getDataPins();
			return $data;
		}

		//Add Data Plan
		public function addDataPlan(){
			extract($_POST);
			$check=$this->model->addDataPlan($network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice);
			if($check == 0){return $this->createNotification1("alert-success","Data Plan Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Plan With Same Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Plan, Please Try Again");}
		}

		//Update Data Plan
		public function updateDataPlan(){
			extract($_POST);
			$check=$this->model->updateDataPlan($plan,$network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice);
			if($check == 0){return $this->createNotification1("alert-success","Plan Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Plan, Please Try Again");}
		}

		//Delete Data Plan
		public function deleteDataPlan(){
			extract($_POST);
			$check=$this->model->deleteDataPlan($id);
			if($check == 0){return 0;}
			else{return 1;}
		}

		//Add Data Pin
		public function addDataPin(){
			extract($_POST);
			$check=$this->model->addDataPin($network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice,$loadpin,$checkbal);
			if($check == 0){return $this->createNotification1("alert-success","Data Pin Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Data Pin With Same Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Data Pin, Please Try Again");}
		}

		//Update Data Pin
		public function updateDataPin(){
			extract($_POST);
			$check=$this->model->updateDataPin($pin,$network,$dataname,$datatype,$planid,$duration,$price,$userprice,$agentprice,$vendorprice,$loadpin,$checkbal);
			if($check == 0){return $this->createNotification1("alert-success","Data Pin Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Data Pin, Please Try Again");}
		}

		//Delete Data Plan
		public function deleteDataPin(){
			extract($_POST);
			$check=$this->model->deleteDataPin($id);
			if($check == 0){return 0;}
			else{return 1;}
		}


		//----------------------------------------------------------------------------------------------------------------
		// Cable Plan Management
		//----------------------------------------------------------------------------------------------------------------

		//Get All Cable Provider
		public function getCableProvider(){
			$data=$this->model->getCableProvider();
			return $data;
		}
		
		//Get Cable Plan
		public function getCablePlans(){
			$data=$this->model->getCablePlans();
			return $data;
		}

		//Add Cable Plan
		public function addCablePlan(){
			extract($_POST);
			$check=$this->model->addCablePlan($provider,$planname,$planid,$duration,$price,$userprice,$agentprice,$vendorprice);
			if($check == 0){return $this->createNotification1("alert-success","Cable Plan Added Successfully");}
			elseif($check == 2){return $this->createNotification1("alert-danger","Cable With Same Id Already Exist.");}
			else{return $this->createNotification1("alert-danger","Unable To Add New Cable, Please Try Again");}
		}

		//Update Data Plan
		public function updateCablePlan(){
			extract($_POST);
			$check=$this->model->updateCablePlan($plan,$provider,$planname,$planid,$duration,$price,$userprice,$agentprice,$vendorprice);
			if($check == 0){return $this->createNotification1("alert-success","Cable Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Cable, Please Try Again");}
		}

		
		//Delete Data Plan
		public function deleteCablePlan(){
			extract($_POST);
			$check=$this->model->deleteCablePlan($id);
			if($check == 0){return 0;}
			else{return 1;}
		} 

		//----------------------------------------------------------------------------------------------------------------
		//Transactions Management
		//----------------------------------------------------------------------------------------------------------------
		//Get All Transactions
		public function getTransactions($limit){
			$data=$this->model->getTransactions($limit);
			return $data;
		}

		//Get All Processing Transactions
		public function getProcessingTransactions($limit){
			$data=$this->model->getProcessingTransactions($limit);
			return $data;
		}

		//Get All Manual Transactions
		public function getManualTransactions($limit){
			$data=$this->model->getManualTransactions($limit);
			return $data;
		}

		//Get Transaction Details
		public function getTransactionDetails(){
			if(!isset($_GET["ref"])){header("Location: ./"); exit(); }
			$data=$this->model->getTransactionDetails($_GET["ref"]);
			return $data;
		}

		//Update Transaction Status
		public function updateTransactionStatus(){
			extract($_POST);
			$check=$this->model->updateTransactionStatus($user,$trans,$transstatus,$amount,$disc);
			if($check == 0){return $this->createNotification1("alert-success","Transaction Status Updated Successfully");}
			else{return $this->createNotification1("alert-danger","Unable To Update Status, Please Try Again");}
		}

		//----------------------------------------------------------------------------------------------------------------
		//Sale Transactions Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Transactions
		public function getSaleTransactions(){
			//Get Transactions
			$service= "All";
			$datefrom = date("Y-m-d 00:00:00");
			$dateto = date("Y-m-d 23:59:59");

			
			if(isset($_POST["filterSalesResult"])){
				
				$datefrom = date("Y-m-d 00:00:00",strtotime($_POST["datefrom"]));
				$dateto = date("Y-m-d 23:59:59",strtotime($_POST["dateto"]));

				$displaydatefrom = date("jS F Y h:iA",strtotime($datefrom));
				$dispalydateto = date("jS F Y h:iA",strtotime($dateto));


				if($_POST["service"] == "Airtime"){
					$data=$this->model->getSaleTransactions("Airtime",$datefrom,$dateto);
					return $this->getAirtimeSalesAnalysis($data,$displaydatefrom,$dispalydateto);
				}
				elseif($_POST["service"] == "Data"){
					$data=$this->model->getSaleTransactions("Data",$datefrom,$dateto);
					return $this->getDataSalesAnalysis($data,$displaydatefrom,$dispalydateto);
				}
				else{
					$data=$this->model->getSaleTransactions($service,$datefrom,$dateto);
					return $this->getGeneralSalesAnalysis($data,$displaydatefrom,$dispalydateto);
				}
			}
			else{

				$displaydatefrom = date("jS F Y h:iA",strtotime($datefrom));
				$dispalydateto = date("jS F Y h:iA",strtotime($dateto));

				$data=$this->model->getSaleTransactions($service,$datefrom,$dateto);
				return $this->getGeneralSalesAnalysis($data,$displaydatefrom,$dispalydateto);
			}
			
		}

		//Get General Sale Analysis
		public function getGeneralSalesAnalysis($data,$datefrom,$dateto){
			
			//Assign Varables To Use For Analysis
			$analysis = array(
				"service" => "general",
				"datefrom" => $datefrom,
				"dateto" => $dateto,
				"transactions" => 0,
				"sales" => 0,
				"profit" => 0,
				"successful" => 0,
				"failed" => 0,
				"airtimeTransactions" => 0,
				"airtimeSales" => 0,
				"airtimeProfit" => 0,
				"dataTransactions" => 0,
				"dataSales" => 0,
				"dataProfit" => 0,
				"dataPinTransactions" => 0,
				"dataPinSales" => 0,
				"dataPinProfit" => 0,
				"cableTransactions" => 0,
				"cableSales" => 0,
				"cableProfit" => 0,
				"electricityTransactions" => 0,
				"electricitySales" => 0,
				"electricityProfit" => 0,
				"examTransactions" => 0,
				"examSales" => 0,
				"examProfit" => 0,
				"manualTransactions" => 0,
				"manualFunding" => 0,
				"monnifyTransactions" => 0,
				"monnifyFunding" => 0,
				"kudaTransactions" => 0,
				"kudaFunding" => 0,
				"airtime2cashTransactions" => 0,
				"airtime2cashSales" => 0,
				"airtime2cashProfit" => 0
			);

			//Compute Sale Analysis
			foreach($data As $sales){

				if($sales->status == 0){
					$analysis["transactions"]++;
					$analysis["sales"]+= (float) $sales->amount;
					$analysis["profit"]+= (float) $sales->profit;
				}
				
				if($sales->status == 0){$analysis["successful"]++; }
				if($sales->status == 1){$analysis["failed"]++; }
				
				if($sales->servicename == "Airtime" && $sales->status == 0){
					$analysis["airtimeTransactions"]++;
					$analysis["airtimeSales"]+= (float) $sales->amount; 
					$analysis["airtimeProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Data" && $sales->status == 0){
					$analysis["dataTransactions"]++;
					$analysis["dataSales"]+= (float) $sales->amount; 
					$analysis["dataProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Data Pin" && $sales->status == 0){
					$analysis["dataPinTransactions"]++;
					$analysis["dataPinSales"]+= (float) $sales->amount; 
					$analysis["dataPinProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Cable TV" && $sales->status == 0){
					$analysis["cableTransactions"]++;
					$analysis["cableSales"]+= (float) $sales->amount; 
					$analysis["cableProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Electricity Bill" && $sales->status == 0){
					$analysis["electricityTransactions"]++;
					$analysis["electricitySales"]+= (float) $sales->amount; 
					$analysis["electricityProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Exam Pin" && $sales->status == 0){
					$analysis["examTransactions"]++;
					$analysis["examSales"]+= (float) $sales->amount; 
					$analysis["examProfit"]+= (float) $sales->profit;
				}

				if($sales->servicename == "Wallet Credit" && $sales->status == 0){
					$analysis["manualTransactions"]++;
					$analysis["manualFunding"]+= (float) $sales->amount; 
				}
				
				if(strpos($sales->servicedesc, 'Monnify') !== false && $sales->status == 0){
					$analysis["monnifyTransactions"]++;
					$analysis["monnifyFunding"]+= (float) $sales->amount; 
				}

				if(strpos($sales->servicedesc, 'Kuda') !== false && $sales->status == 0){
					$analysis["kudaTransactions"]++;
					$analysis["kudaFunding"]+= (float) $sales->amount; 
				}

				if($sales->servicename == "Airtime To Cash" && $sales->status == 0){
					$analysis["airtime2cashTransactions"]++;
					$analysis["airtime2cashSales"]+= (float) $sales->amount; 
					$analysis["airtime2cashProfit"]+= (float) $sales->profit;
				}


			}

			//Format Sales Analysis
			$analysis["sales"] = number_format($analysis["sales"],2);
			$analysis["profit"] = number_format($analysis["profit"],2);
			$analysis["airtimeSales"] = number_format($analysis["airtimeSales"],2);
			$analysis["airtimeProfit"] = number_format($analysis["airtimeProfit"],2);
			$analysis["dataSales"] = number_format($analysis["dataSales"],2);
			$analysis["dataProfit"] = number_format($analysis["dataProfit"],2);
			$analysis["dataPinSales"] = number_format($analysis["dataPinSales"],2);
			$analysis["dataPinProfit"] = number_format($analysis["dataPinProfit"],2);
			$analysis["cableSales"] = number_format($analysis["cableSales"],2);
			$analysis["cableProfit"] = number_format($analysis["cableProfit"],2);
			$analysis["electricitySales"] = number_format($analysis["electricitySales"],2);
			$analysis["electricityProfit"] = number_format($analysis["electricityProfit"],2);
			$analysis["examSales"] = number_format($analysis["examSales"],2);
			$analysis["examProfit"] = number_format($analysis["examProfit"],2);
			$analysis["manualFunding"] = number_format($analysis["manualFunding"],2);
			$analysis["monnifyFunding"] = number_format($analysis["monnifyFunding"],2);
			$analysis["kudaFunding"] = number_format($analysis["kudaFunding"],2);
			$analysis["airtime2cashSales"] = number_format($analysis["airtime2cashSales"],2);
			$analysis["airtime2cashProfit"] = number_format($analysis["airtime2cashProfit"],2);
			

			return $analysis;
		}

		//Get GenerAirtimeal Sale Analysis
		public function getAirtimeSalesAnalysis($data,$datefrom,$dateto){
			
			//Assign Varables To Use For Analysis
			$analysis = array(
				"service" => "airtime",
				"datefrom" => $datefrom,
				"dateto" => $dateto,
				"transactions" => 0,
				"sales" => 0,
				"profit" => 0,
				"successful" => 0,
				"failed" => 0,
				"mtnTransactions" => 0,
				"mtnSales" => 0,
				"mtnProfit" => 0,
				"airtelTransactions" => 0,
				"airtelSales" => 0,
				"airtelProfit" => 0,
				"gloTransactions" => 0,
				"gloSales" => 0,
				"gloProfit" => 0,
				"9mobileTransactions" => 0,
				"9mobileSales" => 0,
				"9mobileProfit" => 0
			);

			//Compute Sale Analysis
			foreach($data As $sales){

				if($sales->status == 0){
					$analysis["transactions"]++;
					$analysis["sales"]+= (float) $sales->amount;
					$analysis["profit"]+= (float) $sales->profit;
				}
				
				if($sales->status == 0){$analysis["successful"]++; }
				if($sales->status == 1){$analysis["failed"]++; }
				
				if(strpos($sales->servicedesc,"MTN") !== false && $sales->status == 0){
					$analysis["mtnTransactions"]++;
					$analysis["mtnSales"]+= (float) $sales->amount; 
					$analysis["mtnProfit"]+= (float) $sales->profit;
				}

				if(strpos($sales->servicedesc,"AIRTEL") !== false && $sales->status == 0){
					$analysis["airtelTransactions"]++;
					$analysis["airtelSales"]+= (float) $sales->amount; 
					$analysis["airtelProfit"]+= (float) $sales->profit;
				}

				if(strpos($sales->servicedesc,"GLO") !== false && $sales->status == 0){
					$analysis["gloTransactions"]++;
					$analysis["gloSales"]+= (float) $sales->amount; 
					$analysis["gloProfit"]+= (float) $sales->profit;
				}

				if(strpos($sales->servicedesc,"9MOBILE") !== false && $sales->status == 0){
					$analysis["9mobileTransactions"]++;
					$analysis["9mobileSales"]+= (float) $sales->amount; 
					$analysis["9mobileProfit"]+= (float) $sales->profit;
				}

				
			}

			//Format Sales Analysis
			$analysis["sales"] = number_format($analysis["sales"],2);
			$analysis["profit"] = number_format($analysis["profit"],2);
			$analysis["mtnSales"] = number_format($analysis["mtnSales"],2);
			$analysis["mtnProfit"] = number_format($analysis["mtnProfit"],2);
			$analysis["airtelSales"] = number_format($analysis["airtelSales"],2);
			$analysis["airtelProfit"] = number_format($analysis["airtelProfit"],2);
			$analysis["gloSales"] = number_format($analysis["gloSales"],2);
			$analysis["gloProfit"] = number_format($analysis["gloProfit"],2);
			$analysis["9mobileSales"] = number_format($analysis["9mobileSales"],2);
			$analysis["9mobileProfit"] = number_format($analysis["9mobileProfit"],2);
			

			return $analysis;
		}

		//Get Data Sale Analysis
		public function getDataSalesAnalysis($data,$datefrom,$dateto){
			
			//Assign Varables To Use For Analysis
			$analysis = array(
				"service" => "data",
				"datefrom" => $datefrom,
				"dateto" => $dateto,
				"transactions" => 0,
				"sales" => 0,
				"profit" => 0,
				"successful" => 0,
				"failed" => 0,
				"mtnTransactions" => 0,
				"mtnSales" => 0,
				"mtnSalesSize" => 0,
				"mtnProfit" => 0,
				"airtelTransactions" => 0,
				"airtelSales" => 0,
				"airtelSalesSize" => 0,
				"airtelProfit" => 0,
				"gloTransactions" => 0,
				"gloSales" => 0,
				"gloSalesSize" => 0,
				"gloProfit" => 0,
				"9mobileTransactions" => 0,
				"9mobileSales" => 0,
				"9mobileSalesSize" => 0,
				"9mobileProfit" => 0
			);

			//Compute Sale Analysis
			foreach($data As $sales){

				if($sales->status == 0){
					$analysis["transactions"]++;
					$analysis["sales"]+= (float) $sales->amount;
					$analysis["profit"]+= (float) $sales->profit;
				}
				
				if($sales->status == 0){$analysis["successful"]++; }
				if($sales->status == 1){$analysis["failed"]++; }
				
				if(strpos($sales->servicedesc,"MTN") !== false && $sales->status == 0){
					$analysis["mtnTransactions"]++;
					$analysis["mtnSales"]+= (float) $sales->amount; 
					$analysis["mtnProfit"]+= (float) $sales->profit;
					$analysis["mtnSalesSize"]+= (float) $this->calculateDataPlanSize($sales->servicedesc);

				}

				if(strpos($sales->servicedesc,"AIRTEL") !== false && $sales->status == 0){
					$analysis["airtelTransactions"]++;
					$analysis["airtelSales"]+= (float) $sales->amount; 
					$analysis["airtelProfit"]+= (float) $sales->profit;
					$analysis["airtelSalesSize"]+= (float) $this->calculateDataPlanSize($sales->servicedesc);
				}

				if(strpos($sales->servicedesc,"GLO") !== false && $sales->status == 0){
					$analysis["gloTransactions"]++;
					$analysis["gloSales"]+= (float) $sales->amount; 
					$analysis["gloProfit"]+= (float) $sales->profit;
					$analysis["gloSalesSize"]+= (float) $this->calculateDataPlanSize($sales->servicedesc);
				}

				if(strpos($sales->servicedesc,"9MOBILE") !== false && $sales->status == 0){
					$analysis["9mobileTransactions"]++;
					$analysis["9mobileSales"]+= (float) $sales->amount; 
					$analysis["9mobileProfit"]+= (float) $sales->profit;
					$analysis["9mobileSalesSize"]+= (float) $this->calculateDataPlanSize($sales->servicedesc);
				}

				
			}

			//Format Sales Analysis
			$analysis["sales"] = number_format($analysis["sales"],2);
			$analysis["profit"] = number_format($analysis["profit"],2);
			$analysis["mtnSales"] = number_format($analysis["mtnSales"],2);
			$analysis["mtnProfit"] = number_format($analysis["mtnProfit"],2);
			$analysis["airtelSales"] = number_format($analysis["airtelSales"],2);
			$analysis["airtelProfit"] = number_format($analysis["airtelProfit"],2);
			$analysis["gloSales"] = number_format($analysis["gloSales"],2);
			$analysis["gloProfit"] = number_format($analysis["gloProfit"],2);
			$analysis["9mobileSales"] = number_format($analysis["9mobileSales"],2);
			$analysis["9mobileProfit"] = number_format($analysis["9mobileProfit"],2);
			$analysis["mtnSalesSize"] = $analysis["mtnSalesSize"] /1000;
			$analysis["airtelSalesSize"] = $analysis["airtelSalesSize"] /1000;
			$analysis["gloSalesSize"] = $analysis["gloSalesSize"] /1000;
			$analysis["9mobileSalesSize"] = $analysis["9mobileSalesSize"] /1000;
			

			return $analysis;
		}

		//Calculate Data Size
		public function calculateDataPlanSize($check){
			
			$dataplans = [
				"100MB" => "100", "200MB" => "200", "300MB" => "300", "500MB" => "500", "750MB" => "700",
				"1,024MB" => "1000", "2,048MB" => "2000", "3,072MB" => "3000",  "5,120MB" => "5000", "10,240MB" => "10000",
				"1GB" => "1000", "1.5GB" => "1500", "2GB" => "2000", "2.5GB" => "2500",
				"3GB" => "3000", "3.51GB" => "3500", "4GB" => "4000", "4.5GB" => "4500", "5GB" => "5000",
				"10GB" => "10000", "15GB" => "15000", "20GB" => "20000", "25GB" => "25000",
				 "30GB" => "30000","40GB" => "40000"
			];

			foreach($dataplans AS $key => $value){
				if(strpos($check,$key) !== false){
					return $value;
				}
			}
			
			return 0;
		}


		//----------------------------------------------------------------------------------------------------------------
		// Contact
		//----------------------------------------------------------------------------------------------------------------


		//Get Contact Message
		public function getContact(){
			$data=$this->model->getContact();
			return $data;
		}

		//Delete Message
		public function deleteContact(){
			extract($_POST);
			$check=$this->model->deleteContact($id);
			if($check == 0){return 0;}
			else{return 1;}
		}

		//Format Description
		public function formatDescription($data){
			$data=str_replace("\n\r", "<br/>", $data);
			return $data;
		}

		public function formatStatus($value){
			$value=(float) $value;
			$output="";
			if($value == 0){$output="<b class='text-success'>Active</b>"; }
			if($value == 1){$output="<b class='text-danger'>Blocked</b>"; }
			if($value == 2){$output="<b class='text-danger'>Pending Verification</b>"; }
			if($value == 3){$output="<b class='text-warning'>Pending Email Verification</b>"; }
			return $output;
		}

		public function formatTransStatus($value){
			$value=(float) $value;
			$output="";
			if($value == 0){$output="<b class='text-success'>Success</b>"; }
			elseif($value == 5){$output="<b class='text-success'>Processing</b>"; }
			else{$output="<b class='text-danger'>Failed</b>"; }
			return $output;
		}

		//Get Site Statistics
		public function getGeneralSiteReports(){
			$data=$this->model->getGeneralSiteReports();
			return $data;
		}

		//Get Site Wallet Balance
		public function getWalletBalance(){
			
			$api = new ApiAccess;
			$details=$api->model->getApiDetails();
			$model = new WalletBalance();
			
			$data = array();

			$walletOne = $model->getWalletBalance("one",$details); 
			$walletTwo = $model->getWalletBalance("two",$details);
			$walletThree = $model->getWalletBalance("three",$details);
			$walletFour = $model->getWalletBalance("four",$details);
			$walletFive = $model->getWalletBalance("five",$details);
			$walletSix = $model->getWalletBalance("six",$details);

			$data["walletOneBalance"] = $walletOne["balance"]; $data["walletOneProvider"] = $walletOne["provider"];
			$data["walletTwoBalance"] = $walletTwo["balance"]; $data["walletTwoProvider"] = $walletTwo["provider"];
			$data["walletThreeBalance"] = $walletThree["balance"]; $data["walletThreeProvider"] = $walletThree["provider"];
			$data["walletFourBalance"] = $walletFour["balance"]; $data["walletFourProvider"] = $walletFour["provider"];
			$data["walletFiveBalance"] = $walletFive["balance"]; $data["walletFiveProvider"] = $walletFive["provider"];
			$data["walletSixBalance"] = $walletSix["balance"]; $data["walletSixProvider"] = $walletSix["provider"];
			
			$walletOneBalance = (float) str_replace(",","",$walletOne["balance"]);
			$walletTwoBalance = (float) str_replace(",","",$walletTwo["balance"]);
			$walletThreeBalance = (float) str_replace(",","",$walletThree["balance"]);
			$walletFourBalance = (float) str_replace(",","",$walletFour["balance"]);
			$walletFiveBalance = (float) str_replace(",","",$walletFive["balance"]);
			$walletSixBalance = (float) str_replace(",","",$walletSix["balance"]);

			$walletTotal =  $walletOneBalance +  $walletTwoBalance + $walletThreeBalance + $walletFourBalance + $walletFiveBalance + $walletSixBalance;
			$data["walletTotal"] = number_format($walletTotal,2);

			return $data;
		}

		//----------------------------------------------------------------------------------------------------------------
		// Kuda Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Withdraw Kuda Funds
		public function withdrawKudaFunds(){
			extract($_POST);
			$obj = new Account;
			$userAmount=$userAmount."00";
			$data = $obj->completeKudaFundingByWithdrawal($userAmount,$userEmail);
			return $this->createNotification1("alert-success","Withdrawal Request Sent Successfully, Please Confirm The Withdral On Your Kuda Dashboard");
		}

		//----------------------------------------------------------------------------------------------------------------
		// Airtime To Cash Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Pending Transactions
		public function getPendingAirtimeToCash(){
			$data=$this->model->getPendingAirtimeToCash();
			return $data;
		}
		
		//Get All Transactions
		public function getAllAirtimeToCash($limit){
			$data=$this->model->getAllAirtimeToCash($limit);
			return $data;
		}
		
		//Update Airtime To Cash Status
		public function updateAirtimeToCashStatus(){
			extract($_POST);
			$check=$this->model->updateAirtimeToCashStatus($id,$status);
			if($check == 0){return 0; } else{return 1; }
		}
		
		//----------------------------------------------------------------------------------------------------------------
		// Manual Funding Management
		//----------------------------------------------------------------------------------------------------------------
		
		//Get All Pending Manula Funding
		public function getPendingManualFund(){
			$data=$this->model->getPendingManualFund();
			return $data;
		}
		
		
		//Update Manual Fund Status
		public function updateManualFundStatus(){
			extract($_POST);
			$check=$this->model->updateManualFundStatus($id,$email,$amount,$status);
			if($check == 0){return 0; } else{return 1; }
		}
		
		

		
		

	}

?>