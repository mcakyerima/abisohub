 <?php

	class Account extends Model
	{

		//Verify Admin Login Deatils
		public function verifyAdminAccount($uname, $pass, $pin)
		{
			$sql = "SELECT * FROM sysusers WHERE sysUsername=:uname AND sysToken=:password";
			if (!empty($pin)) {
				$pin = substr(sha1(md5($pin)), 3, 10);
				$sql .= " AND sysPinToken=:token";
			}

			$query = $this->connect()->prepare($sql);
			$query->bindParam(':uname', $uname, PDO::PARAM_STR);
			$query->bindParam(':password', $pass, PDO::PARAM_STR);
			if (!empty($pin)) {
				$query->bindParam(':token', $pin, PDO::PARAM_STR);
			}
			$query->execute();

			$result = $query->fetch(PDO::FETCH_ASSOC);

			if ($query->rowCount() > 0) {

				if ($result["sysStatus"] <> 0) {
					return json_encode(["status" => "blocked"]);
				}
				if ($result["sysPinStatus"] == 1 && empty($pin)) {
					return json_encode(["status" => "pinrequired"]);
				}

				$_SESSION['sysUser'] = $result["sysUsername"];
				$_SESSION['sysRole'] = $result["sysRole"];
				$_SESSION['sysName'] = $result["sysName"];
				$_SESSION['sysId'] = $result["sysId"];
				return json_encode(["status" => "success"]);
			} else {
				return json_encode(["status" => "invalid"]);
			}
		}

		public function verifyAdminAccount2()
		{
			$sql = "SELECT sysId,sysName,sysStatus,sysUsername,sysRole FROM sysusers";
			$query = $this->connect()->prepare($sql);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			return $result;
		}

		/**
		 * Register/Create New User Account
		 * 
		 * @param string $fname First Name of the user
		 * @param string $lname Last Name of the user
		 * @param string $email Email address of the user
		 * @param string $phone Phone number of the user
		 * @param string $password Password for the account
		 * @param string $state State of the user
		 * @param string $account Account type
		 * @param string $referal Referral code
		 * @param string $transpin Transaction PIN
		 * @param bool $isApiRequest Flag indicating if the request is an API request
		 * 
		 * @return string JSON encoded response
		 */
		public function registerUser($fname, $lname, $email, $phone, $password, $state, $account, $referal, $transpin, $isApiRequest = false)
		{
			$dbh = $this->connect();

			// Verify registration details
			$c = "SELECT sEmail, sPhone, sType FROM subscribers WHERE ";
			$c .= ($email <> "") ? "sEmail=:e OR sPhone=:p" : "sPhone=:p";
			$queryC = $dbh->prepare($c);
			if ($email <> "") {
				$queryC->bindParam(':e', $email, PDO::PARAM_STR);
			}
			$queryC->bindParam(':p', $phone, PDO::PARAM_STR);
			$queryC->execute();
			$result = $queryC->fetch(PDO::FETCH_ASSOC);

			if ($queryC->rowCount() > 0) {
				// Output error message if data already exists
				if ($result["sPhone"] == $phone) {
					$data = ["status" => "error", "msg" => "Phone Number Already Exist"];
				} else if ($email <> "" && $result["sEmail"] == $email) {
					$data = ["status" => "error", "msg" => "Email Already Exist"];
				} else if ($result["sEmail"] == $email && $result["sPhone"] == $phone) {
					$data = ["status" => "error", "msg" => "Phone Number And Email Already Exist"];
				}
				return json_encode($data);
			}

			// Insert and register member
			$hash = substr(sha1(md5($password)), 3, 10);
			$apiKey = substr(str_shuffle("0123456789ABCDEFGHIJklmnopqrstvwxyzAbAcAdAeAfAgAhBaBbBcBdC1C23C3C4C5C6C7C8C9xix2x3"), 0, 60) . time();
			$varCode = mt_rand(2000, 9000);

			$sql = "INSERT INTO subscribers (sFname, sLname, sEmail, sPhone, sPass, sState, sType, sApiKey, sReferal, sPin, sVerCode, sRegStatus)
					VALUES (:fname, :lname, :email, :phone, :pass, :s, :a, :k, :ref, :pin, :code, 0)";
			$query = $dbh->prepare($sql);

			$query->bindParam(':fname', $fname, PDO::PARAM_STR);
			$query->bindParam(':lname', $lname, PDO::PARAM_STR);
			$query->bindParam(':email', $email, PDO::PARAM_STR);
			$query->bindParam(':phone', $phone, PDO::PARAM_STR);
			$query->bindParam(':pass', $hash, PDO::PARAM_STR);
			$query->bindParam(':s', $state, PDO::PARAM_STR);
			$query->bindParam(':a', $account, PDO::PARAM_STR);
			$query->bindParam(':k', $apiKey, PDO::PARAM_STR);
			$query->bindParam(':ref', $referal, PDO::PARAM_STR);
			$query->bindParam(':pin', $transpin, PDO::PARAM_INT);
			$query->bindParam(':code', $varCode, PDO::PARAM_STR);
			$query->execute();

			$lastInsertId = $dbh->lastInsertId();

			if ($lastInsertId) {
				if (!$isApiRequest) {
					// Web requests: Set session and cookies
					$_SESSION["loginId"] = $lastInsertId;
					$_SESSION["loginName"] = $fname . " " . $lname;
					$_SESSION["loginEmail"] = $email;
					$_SESSION["loginPhone"] = $phone;

					$loginId = base64_encode($lastInsertId);
					$loginState = base64_encode($state);
					$loginPhone = base64_encode($phone);
					$loginAccount = base64_encode("1");
					$loginName = base64_encode($fname);

					setcookie("loginId", $loginId, time() + (2592000 * 30), "/");
					setcookie("loginState", $loginState, time() + (2592000 * 30), "/");
					setcookie("loginAccount", $loginAccount, time() + (2592000 * 30), "/");
					setcookie("loginPhone", $loginPhone, time() + (31540000 * 30), "/");
					setcookie("loginName", $loginName, time() + (31540000 * 30), "/");

					$randomToken = substr(str_shuffle("ABCDEFGHIJklmnopqrstvwxyz"), 0, 10);
					$userLoginToken = time() . $randomToken . mt_rand(100, 1000);
					$_SESSION["loginAccToken"] = $userLoginToken;

					$sqlAc = "INSERT INTO userlogin (user, token) VALUES (:user, :token)";
					$queryAc = $dbh->prepare($sqlAc);
					$queryAc->bindParam(':user', $lastInsertId, PDO::PARAM_STR);
					$queryAc->bindParam(':token', $userLoginToken, PDO::PARAM_STR);
					$queryAc->execute();
				}

				// Get API details
				$d = $this->getApiConfiguration();
				$a = $this->getSiteConfiguration();
				$monifyStatus = $this->getConfigValue($d, "monifyStatus");
				$monifyApi = $this->getConfigValue($d, "monifyApi");
				$monifySecrete = $this->getConfigValue($d, "monifySecrete");
				$monifyContract = $this->getConfigValue($d, "monifyContract");
				// $adminEmail = $a->email;

				// If Monnify is active, create virtual account for user
				if ($monifyStatus == "On") {
					$this->createVirtualBankAccount($lastInsertId, $fname, $lname, $email, $monifyApi, $monifySecrete, $monifyContract);
				}

				// Prepare response
				$data = [
					"status" => "success",
					"msg" => "Registration successful",
					"apiKey" => $apiKey,
					"token" => isset($userLoginToken) ? $userLoginToken : null,
					"pin" => isset($transpin) ? $transpin : "",
					"userId" => $lastInsertId
				];
			} else {
				$data = ["status" => "fail", "msg" => "Unexpected Error, Please Try Again Later"];
			}

			return json_encode($data);
		}


		// //Register/Create New User Account
		// public function registerUser($fname,$lname,$email,$phone,$password,$state,$account,$referal,$transpin){

		// 	//if registration is done by admin, dont save cookies data
		// 	if($referal == "admin"){$saveCookies=FALSE; $referal="";}else{$saveCookies=TRUE;}

		// 	//Verify Registration Details
		// 	$dbh=$this->connect();
		// 	$c="SELECT sEmail,sPhone,sType FROM subscribers WHERE ";
		// 	$c.= ($email<>"") ? " sEmail=:e OR sPhone=:p" : " sPhone=:p";
		// 	$queryC = $dbh->prepare($c);
		// 	if($email<>""){$queryC->bindParam(':e',$email,PDO::PARAM_STR);}
		//  	$queryC->bindParam(':p',$phone,PDO::PARAM_STR);
		//  	$queryC->execute();
		//   	$result=$queryC->fetch(PDO::FETCH_ASSOC);
		//   	$data=4;

		//   	//Output Error Message If Data Already Exist
		//   	if($queryC->rowCount() > 0){

		//       if($result["sPhone"] == $phone){$data = ["status" => "error", "msg" => "Phone Number Already Exist"]; }
		//       if($email<>""){if($result["sEmail"] == $email){ $data = ["status" => "error", "msg" => "Email Already Exist"]; }}
		//       if($result["sEmail"] == $email && $result["sPhone"] == $phone){$data =  ["status" => "error", "msg" => "Phone Number And Email Already Exist"]; }

		//       return (object) $data; 
		//   	}

		//   	//Insert And Register Member
		//   	else{

		// 		$hash=substr(sha1(md5($password)), 3, 10);
		// 		$apiKey = substr(str_shuffle("0123456789ABCDEFGHIJklmnopqrstvwxyzAbAcAdAeAfAgAhBaBbBcBdC1C23C3C4C5C6C7C8C9xix2x3"), 0, 60).time();
		// 		$varCode=mt_rand(2000,9000);


		//        $sql="INSERT INTO subscribers (sFname,sLname,sEmail,sPhone,sPass,sState,sType,sApiKey,sReferal,sPin,sVerCode,sRegStatus)VALUES(:fname,:lname,:email,:phone,:pass,:s,:a,:k,:ref,:pin,:code,0)";

		//        $query = $dbh->prepare($sql);

		//        $query->bindParam(':fname',$fname,PDO::PARAM_STR);
		//        $query->bindParam(':lname',$lname,PDO::PARAM_STR);
		//        $query->bindParam(':email',$email,PDO::PARAM_STR);
		//        $query->bindParam(':phone',$phone,PDO::PARAM_STR);
		//        $query->bindParam(':pass',$hash,PDO::PARAM_STR);
		//        $query->bindParam(':s',$state,PDO::PARAM_STR);
		//        $query->bindParam(':a',$account,PDO::PARAM_STR);
		//        $query->bindParam(':k',$apiKey,PDO::PARAM_STR);
		//        $query->bindParam(':ref',$referal,PDO::PARAM_STR);
		//        $query->bindParam(':pin',$transpin,PDO::PARAM_INT);
		//        $query->bindParam(':code',$varCode,PDO::PARAM_STR);
		//        $query->execute();

		//        $lastInsertId = $dbh->lastInsertId();
		//        if($lastInsertId){

		// 			$data=0; 

		// 			if($saveCookies){
		// 				$_SESSION["loginId"]=$lastInsertId;
		// 				$_SESSION["loginName"]=$fname . " " . $lname;
		// 				$_SESSION["loginEmail"]=$email;
		// 				$_SESSION["loginPhone"]=$phone;

		// 				$loginId=base64_encode($lastInsertId);
		// 				$loginState=base64_encode($state);
		// 				$loginPhone=base64_encode($phone);
		// 				$loginAccount=base64_encode("1");
		// 				$loginName=base64_encode($fname);


		// 				setcookie("loginId", $loginId, time() + (2592000 * 30), "/");
		// 				setcookie("loginState", $loginState, time() + (2592000 * 30), "/");
		// 				setcookie("loginAccount", $loginAccount, time() + (2592000 * 30), "/");
		// 				setcookie("loginPhone", $loginPhone, time() + (31540000 * 30), "/");
		// 				setcookie("loginName", $loginName, time() + (31540000 * 30), "/");


		// 				//Generate User Login Token
		// 				$randomToken = substr(str_shuffle("ABCDEFGHIJklmnopqrstvwxyz"), 0, 10);
		// 				$userLoginToken = time() . $randomToken . mt_rand(100,1000);

		// 				//Set User Login Token
		// 				$_SESSION["loginAccToken"]=$userLoginToken;

		// 				//Save New User Login Token For One Device Login Check

		// 				$sqlAc="INSERT INTO userlogin (user,token) VALUES (:user,:token)";
		// 				$queryAc = $dbh->prepare($sqlAc);
		// 				$queryAc->bindParam(':user',$lastInsertId,PDO::PARAM_STR);
		// 				$queryAc->bindParam(':token',$userLoginToken,PDO::PARAM_STR);
		// 				$queryAc->execute();
		// 			}

		// 			//Get API Details
		// 			$d=$this->getApiConfiguration();
		// 			$a=$this->getSiteConfiguration();
		// 			$monifyStatus = $this->getConfigValue($d,"monifyStatus");
		// 			$monifyApi = $this->getConfigValue($d,"monifyApi");
		// 			$monifySecrete = $this->getConfigValue($d,"monifySecrete");
		// 			$monifyContract = $this->getConfigValue($d,"monifyContract");
		// 			$adminEmail = $a->email;

		// 			//If Monnify Is Active, Create Virtual Account For User
		// 			if($monifyStatus == "On"){
		// 				$this->createVirtualBankAccount($lastInsertId,$fname,$lname,$email,$monifyApi,$monifySecrete,$monifyContract);
		// 			}

		// 			//Send Email To User
		// 			$subject="Welcome (".$this->sitename.")";
		// 			$message="Hi ".$fname.", "."Welcome to {$this->sitename}. At {$this->sitename}, you can access instant recharge of Airtime, Data Bundle, CableTv, Electricity Bill Payment and Airtime to Cash. More features such as buying and selling gift cards, wallet to wallet transfer, and wallet to bank transfer would be made available soon. Our customer support line is available to you 24/7. Stay connected.";
		// 			$check=self::sendMail($email,$subject,$message);

		// 			//Send Email To Admin
		// 			$subject2="New User Registration (".$this->sitename.")";
		// 			$message2="Hi ".$this->sitename.", "."This is to notify you that a new user just registered on your platform. Please find the below details for your usage: ";
		// 			$message2.="<h3>Name: $fname $lname <br/> Phone Number: $phone <br/> Email: $email <br> State: $state</h3>";
		// 			$message2.="<br/><br/><br/> <i>Notification Powered By InTranX</i>";
		// 			$check=self::sendMail($adminEmail,$subject2,$message2);

		// 			$data =  ["status" => "success", "msg" => "Registartion Successfull"];


		//        } 
		//        else{$data =  ["status" => "fail", "msg" => "Unexpected Error, Please Try Again Later"]; }

		// 	   return (object) $data;
		// 	}
		// }

		//Login User Account
		public function loginUser($phone, $key)
		{

			//Verify Registration Details
			$dbh = $this->connect();
			$hash = substr(sha1(md5($key)), 3, 10);
			$c = "SELECT sId,sFname,sLname,sEmail,sPass,sPhone,sState,sType,sRegStatus FROM subscribers WHERE sPhone=:ph AND sPass=:p";
			$queryC = $dbh->prepare($c);
			$queryC->bindParam(':ph', $phone, PDO::PARAM_STR);
			$queryC->bindParam(':p', $hash, PDO::PARAM_STR);
			$queryC->execute();
			$result = $queryC->fetch(PDO::FETCH_OBJ);
			if ($queryC->rowCount() > 0) {

				if ($result->sRegStatus == 1) {
					return (object) ["status" => "fail", "msg" => "Account Blocked, Please Contact Customer Support For Additional Information"];
				}

				$_SESSION["loginId"] = $result->sId;
				$_SESSION["loginName"] = $result->sFname . " " . $result->sLname;
				$_SESSION["loginEmail"] = $result->sEmail;
				$_SESSION["loginPhone"] = $result->sPhone;


				$loginId = base64_encode($result->sId);
				$loginState = base64_encode($result->sState);
				$loginAccount = base64_encode($result->sType);
				$loginPhone = base64_encode($result->sPhone);
				$loginName = base64_encode($result->sFname);

				setcookie("loginId", $loginId, time() + (2592000 * 30), "/");
				setcookie("loginState", $loginState, time() + (2592000 * 30), "/");
				setcookie("loginAccount", $loginAccount, time() + (2592000 * 30), "/");
				setcookie("loginPhone", $loginPhone, time() + (31540000 * 30), "/");
				setcookie("loginName", $loginName, time() + (31540000 * 30), "/");

				//Generate User Login Token
				$randomToken = substr(str_shuffle("ABCDEFGHIJklmnopqrstvwxyz"), 0, 10);
				$userLoginToken = time() . $randomToken . mt_rand(100, 1000);

				//Set User Login Token
				$_SESSION["loginAccToken"] = $userLoginToken;

				//Save New User Login Token For One Device Login Check

				$sqlAc = "INSERT INTO userlogin (user,token) VALUES (:user,:token)";
				$queryAc = $dbh->prepare($sqlAc);
				$queryAc->bindParam(':user', $result->sId, PDO::PARAM_STR);
				$queryAc->bindParam(':token', $userLoginToken, PDO::PARAM_STR);
				$queryAc->execute();

				//Login Notification

				//Send Email To User
				$subject = "Login Notification (" . $this->sitename . ")";
				$message = "<h3><b>Welcome Back " . $result->sFname . "! </h3></b> <br/><br/> ";
				$message .= "You have successfully logged in to your {$this->sitename} account at ";
				$message .= date("d M Y h:iA") . ". <br/><br/>";
				$message .= "If you think this action is suspicious, please change your password immediadtely and reach out to our customer support team. <br/><br/>";
				$message .= "<b>Why send this email?</b> We take security very seriously and we want to keep you in the loop of activities on your account.";
				$check = self::sendMail($result->sEmail, $subject, $message);


				return (object) ["status" => "success", "msg" => "Login Successfull"];
			} else {
				return (object) ["status" => "fail", "msg" => "Invalid Username Or Password"];
			}
		}


		//Login User Account
		public function loginUserFingerPrint($phone, $key)
		{

			//Verify Registration Details
			$dbh = $this->connect();
			$hash = substr(sha1(md5($key)), 3, 10);
			$c = "SELECT sId,sFname,sLname,sApiKey,sEmail,sPass,sPin,sPhone,sState,sType,sRegStatus FROM subscribers WHERE sPhone=:ph AND sPass=:p";
			$queryC = $dbh->prepare($c);
			$queryC->bindParam(':ph', $phone, PDO::PARAM_STR);
			$queryC->bindParam(':p', $hash, PDO::PARAM_STR);
			$queryC->execute();
			$result = $queryC->fetch(PDO::FETCH_OBJ);
			if ($queryC->rowCount() > 0) {

				if ($result->sRegStatus == 1) {
					$response = array();
					$response["status"] = 2;
					return $response;
				}

				$_SESSION["loginId"] = $result->sId;
				$_SESSION["loginName"] = $result->sFname . " " . $result->sLname;
				$_SESSION["loginEmail"] = $result->sEmail;
				$_SESSION["loginPhone"] = $result->sPhone;


				$loginId = base64_encode($result->sId);
				$loginState = base64_encode($result->sState);
				$loginAccount = base64_encode($result->sType);
				$loginPhone = base64_encode($result->sPhone);
				$loginName = base64_encode($result->sFname);

				setcookie("loginId", $loginId, time() + (2592000 * 30), "/");
				setcookie("loginState", $loginState, time() + (2592000 * 30), "/");
				setcookie("loginAccount", $loginAccount, time() + (2592000 * 30), "/");
				setcookie("loginPhone", $loginPhone, time() + (31540000 * 30), "/");
				setcookie("loginName", $loginName, time() + (31540000 * 30), "/");

				//Generate User Login Token
				$randomToken = substr(str_shuffle("ABCDEFGHIJklmnopqrstvwxyz"), 0, 10);
				$userLoginToken = time() . $randomToken . mt_rand(100, 1000);

				//Set User Login Token
				$_SESSION["loginAccToken"] = $userLoginToken;

				//Save New User Login Token For One Device Login Check

				$sqlAc = "INSERT INTO userlogin (user,token) VALUES (:user,:token)";
				$queryAc = $dbh->prepare($sqlAc);
				$queryAc->bindParam(':user', $result->sId, PDO::PARAM_STR);
				$queryAc->bindParam(':token', $userLoginToken, PDO::PARAM_STR);
				$queryAc->execute();

				$response = array();
				$response["status"] = 0;
				$response["fname"] = $result->sFname;
				$response["lname"] = $result->sLname;
				$response["email"] = $result->sEmail;
				$response["phone"] = $result->sPhone;
				$response["state"] = $result->sState;
				$response["apiKey"] = $result->sApiKey;
				$response["userId"] = $result->sId;
				$response["pin"] = $result->sPin;

				return $response;
			} else {
				$response = array();
				$response["status"] = 1;
				return $response;
			}
		}

		//Recover User Account
		public function recoverUserLogin($email, $isApiRequest = false)
		{
			//Verify Registration Details
			$dbh = $this->connect();
			$c = "SELECT sId,sFname,sLname,sEmail,sPass FROM subscribers WHERE sEmail=:e";
			$queryC = $dbh->prepare($c);
			$queryC->bindParam(':e', $email, PDO::PARAM_STR);
			$queryC->execute();
			$result = $queryC->fetch(PDO::FETCH_OBJ);
			if ($queryC->rowCount() > 0) {

				//Genereate And Update Verification Code
				$varCode = mt_rand(2000, 9000);
				$stmt = "UPDATE subscribers SET sVerCode=$varCode WHERE sId=$result->sId";
				$query = $dbh->prepare($stmt);
				$query->execute();

				if (!$isApiRequest) {
					//Send Verification Code To User Email
					$email = $result->sEmail;
					$subject = "Account Recovery (" . $this->sitename . ")";
					$message = "<h3>Hi " . $result->sFname . ", You Recently Requested For A Password Recovery. Use The Verification Code \"" . $varCode . "\" To Recover Your Account. Thank You For Using " . $this->sitename . ".</h3>";
					$check = self::sendMail($email, $subject, $message);
					if ($check == 0) {
						return 0;
					} else {
						return 3;
					}
				} else {
					$response = array();
					$response['status'] = 0;
					$response['code'] = $varCode;
					return $response;
				}
			} else {
				return 1;
			}
		}

		//Recover User Account
		public function verifyRecoveryCode($email, $code)
		{

			//Verify Registration Details
			$dbh = $this->connect();
			$c = "SELECT sId FROM subscribers WHERE sEmail=:e AND sVerCode=:c";
			$queryC = $dbh->prepare($c);
			$queryC->bindParam(':e', $email, PDO::PARAM_STR);
			$queryC->bindParam(':c', $code, PDO::PARAM_STR);
			$queryC->execute();
			if ($queryC->rowCount() > 0) {
				return 0;
			} else {
				return 1;
			}
		}

		//Recover Seller Account
		public function updateUserKey($email, $code, $key)
		{

			//Verify Registration Details
			$dbh = $this->connect();
			$hash = substr(sha1(md5($key)), 3, 10);
			$verCode = mt_rand(1000, 9999);
			$c = "UPDATE subscribers SET sPass=:k,sVerCode=:v WHERE sEmail=:e AND sVerCode=:c";
			$queryC = $dbh->prepare($c);
			$queryC->bindParam(':e', $email, PDO::PARAM_STR);
			$queryC->bindParam(':c', $code, PDO::PARAM_STR);
			$queryC->bindParam(':k', $hash, PDO::PARAM_STR);
			$queryC->bindParam(':v', $verCode, PDO::PARAM_INT);
			if ($queryC->execute()) {
				return 0;
			} else {
				return 1;
			}
		}

		public function createVirtualBankAccount($id, $fname, $lname, $email, $monnifyApi, $monnifySecret, $monnifyContract)
		{
			$fullname = $fname . " " . $lname;
			$accessKey = "$monnifyApi:$monnifySecret";
			$apiKey = base64_encode($accessKey);

			// Get Authorization Data
			$url = 'https://api.monnify.com/api/v1/auth/login';
			$url2 = "https://api.monnify.com/api/v2/bank-transfer/reserved-accounts";
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Basic {$apiKey}",
				),
			));

			$json = curl_exec($ch);
			$result = json_decode($json);
			curl_close($ch);

			// Check if responseBody exists
			if (isset($result->responseBody)) {
				$accessToken = $result->responseBody->accessToken;
			} else {
				// Handle missing responseBody
				return json_encode(["status" => "error", "msg" => "Failed to get access token"]);
			}

			$ref = uniqid() . rand(1000, 9000);

			// Request Account Creation
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => json_encode([
					"accountReference" => $ref,
					"accountName" => $fullname,
					"currencyCode" => "NGN",
					"contractCode" => $monnifyContract,
					"customerEmail" => $email,
					"bvn" => "22433145825",
					"customerName" => $fullname,
					"getAllAvailableBanks" => false,
					"preferredBanks" => ["50515", "232", "070", "035"]
				]),
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer " . $accessToken,
					"Content-Type: application/json"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$value = json_decode($response, true);

			// Check for requestSuccessful key and responseBody
			if (isset($value["requestSuccessful"]) && $value["requestSuccessful"] == true) {
				$wema = "";
				$sterling = "";
				$monipoint = "";
				$fidelity = "";
				$wema_name = "";
				$ref = "";

				foreach ($value["responseBody"]["accounts"] as $account) {
					$bankCode = $account["bankCode"];
					$accountNumber = $account["accountNumber"];
					$bankName = $account["bankName"];

					switch ($bankCode) {
						case "50515":
							$monipoint = $accountNumber;
							break;
						case "070":
							$fidelity = $accountNumber;
							break;
						case "232":
							$sterling = $accountNumber;
							break;
						case "035":
							$wema = $accountNumber;
							$wema_name = $bankName;
							break;
					}
				}
				if (!empty($value["responseBody"]["accountReference"])) {
					$ref = $value["responseBody"]["accountReference"];
				}

				// Save Account Number
				$dbh = self::connect();
				$c = "UPDATE subscribers SET sRolexBank=:rb, sSterlingBank=:sb, sBankNo=:wb, sBankName=:bn, sFidelityBank=:fb, sAccountref=:ar WHERE sId=$id";
				$queryC = $dbh->prepare($c);
				$queryC->bindParam(':rb', $monipoint, PDO::PARAM_STR);
				$queryC->bindParam(':sb', $sterling, PDO::PARAM_STR);
				$queryC->bindParam(':wb', $wema, PDO::PARAM_STR);
				$queryC->bindParam(':bn', $wema_name, PDO::PARAM_STR);
				$queryC->bindParam(':fb', $fidelity, PDO::PARAM_STR);
				$queryC->bindParam(':ar', $ref, PDO::PARAM_STR);
				$queryC->execute();
			} else {
				// Handle request failure
				return json_encode(["status" => "error", "msg" => "Failed to create virtual account"]);
			}
		}

		// //Create Virtual Bank Account
		// public function createVirtualBankAccount($id,$fname,$lname,$email,$monnifyApi,$monnifySecret,$monnifyContract){

		// 	$fullname = $fname." ".$lname;
		// 	$accessKey = "$monnifyApi:$monnifySecret";
		// 	$apiKey = base64_encode($accessKey);

		// 	//Get Authorization Data
		// 	$url = 'https://api.monnify.com/api/v1/auth/login';
		// 	//$url = "https://sandbox.monnify.com/api/v1/auth/login/";
		// 	$url2 = "https://api.monnify.com/api/v2/bank-transfer/reserved-accounts";
		// 	//$url2 = "https://sandbox.monnify.com/api/v2/bank-transfer/reserved-accounts";
		// 	$ch = curl_init();
		// 	curl_setopt_array($ch, array(
		// 		CURLOPT_URL => $url,
		// 		CURLOPT_RETURNTRANSFER => true,
		// 		CURLOPT_TIMEOUT => 0,
		// 		CURLOPT_FOLLOWLOCATION => true,
		// 		CURLOPT_CUSTOMREQUEST => 'POST',
		// 		CURLOPT_HTTPHEADER => array(
		// 			"Authorization: Basic {$apiKey}",
		// 		),
		// 	));


		// 	$json = curl_exec($ch);
		// 	$result = json_decode($json);
		// 	curl_close($ch);

		// 	$accessToken=$result->responseBody->accessToken;
		// 	$ref=uniqid().rand(1000, 9000);

		// 	//Request Account Creation
		// 	$curl = curl_init();

		// 	curl_setopt_array($curl, array(
		// 		CURLOPT_URL =>  $url2,
		// 		CURLOPT_RETURNTRANSFER => true,
		// 		CURLOPT_ENCODING => "",
		// 		CURLOPT_MAXREDIRS => 10,
		// 		CURLOPT_TIMEOUT => 0,
		// 		CURLOPT_FOLLOWLOCATION => true,
		// 		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		// 		CURLOPT_CUSTOMREQUEST => "POST",
		// 		CURLOPT_POSTFIELDS => 
		// 							'{
		// 									"accountReference": "'.$ref.'",
		// 									"accountName": "'.$fullname.'",
		// 									"currencyCode": "NGN",
		// 									"contractCode": "'.$monnifyContract.'",
		// 									"customerEmail": "'.$email.'",
		// 									"bvn": "22433145825",
		// 									"customerName": "'.$fullname.'",
		// 									"getAllAvailableBanks": false,
		// 									"preferredBanks": ["50515","232","070","035"]

		// 							}',
		// 		CURLOPT_HTTPHEADER => array(
		// 			"Authorization: Bearer ".$accessToken,
		// 			"Content-Type: application/json"
		// 		),
		// 	));

		// 	$response = curl_exec($curl);
		// 	curl_close($curl);
		// 	$value = json_decode($response, true);

		// 	//Check And Save Account Details
		// 	if($value["requestSuccessful"] == true){
		// 		// $account_name  = $value["responseBody"]["accountName"];
		// 		$wema=""; $sterling=""; $monipoint=""; $fidelity=""; $wema_name=""; $ref="";

		// 	foreach ($value["responseBody"]["accounts"] as $account) {
		// 		$bankCode = $account["bankCode"];
		// 		$accountNumber = $account["accountNumber"];
		// 		$bankName = $account["bankName"];

		// 		switch ($bankCode) {
		// 			case "50515":
		// 				$monipoint = $accountNumber;
		// 				break;
		// 			case "070":
		// 				$fidelity = $accountNumber;
		// 				break;
		// 			case "232":
		// 				$sterling = $accountNumber;
		// 				break;
		// 			case "035":
		// 				$wema = $accountNumber;
		// 				$wema_name = $bankName;
		// 				break;
		// 		}
		// 	}
		// 	if (!empty($value["responseBody"]["accountReference"])) {
		// 		$ref = $value["responseBody"]["accountReference"];
		// 	}	



		// 		//Save Account Number

		// 		$dbh=self::connect();
		// 				$c="UPDATE subscribers SET sRolexBank=:rb,sSterlingBank=:sb,sBankNo=:wb,sBankName=:bn,sFidelityBank=:fb,sAccountref=:ar WHERE sId=$id";
		// 				$queryC = $dbh->prepare($c);
		// 				$queryC->bindParam(':rb',$monipoint,PDO::PARAM_STR);
		// 				$queryC->bindParam(':sb',$sterling,PDO::PARAM_STR);
		// 				$queryC->bindParam(':wb',$wema,PDO::PARAM_STR);
		// 				$queryC->bindParam(':bn',$wema_name,PDO::PARAM_STR);
		// 				$queryC->bindParam(':fb',$fidelity,PDO::PARAM_STR);
		// 				$queryC->bindParam(':ar',$ref,PDO::PARAM_STR);
		// 				$queryC->execute();
		// 	}
		// }

		//Verify Vitual Bank Account
		public function bvnVerification($id, $sAccountref, $bvn, $monnifyApi, $monnifySecret)
		{

			$accessKey = "$monnifyApi:$monnifySecret";
			$apiKey = base64_encode($accessKey);
			$accountReference = $sAccountref;

			//Get Authorization Data
			$url = 'https://api.monnify.com/api/v1/auth/login';
			//$url = "https://sandbox.monnify.com/api/v1/auth/login/";
			$url2 = 'https://api.monnify.com/api/v1/bank-transfer/reserved-accounts/' . $accountReference . '/kyc-info';
			//$url3 = 'https://api.monnify.com/api/v1/bank-transfer/reserved-accounts/' . $accountReference . '/kyc-info';
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Basic {$apiKey}",
				),
			));


			$json = curl_exec($ch);
			$result = json_decode($json);
			curl_close($ch);

			$accessToken = $result->responseBody->accessToken;

			//Update Monnify KYC VErification
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'PUT',
				CURLOPT_POSTFIELDS => '{
				"bvn":"' . $bvn . '"
			}',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer " . $accessToken,
					"Content-Type: application/json"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$value = json_decode($response, true);
			// echo $response;

			// Check if Monnify request was successful
			if ($value["requestSuccessful"] == true) {
				// Check if the BVN verification is successful
				if (isset($value["responseBody"]["bvn"]) && !empty($value["responseBody"]["bvn"])) {
					// BVN is valid, update the database
					$pverified = $value["responseBody"]["bvn"];;
					$verified = base64_encode($pverified);

					// Update the database with the verified BVN
					$dbh = self::connect();
					$c = "UPDATE subscribers SET sVerified=:bv WHERE sId=$id";
					$queryC = $dbh->prepare($c);
					$queryC->bindParam(':bv', $verified, PDO::PARAM_STR);
					$queryC->execute();

					// Return 0 for success
					return 0;
				} else {
					// BVN is not valid, respond with error, return 1
					return 1;
				}
			} else {
				// Request to Monnify failed, respond with error, return 2
				return 2;
			}
		}

		//Create Payvessel Virtual Bank Account
		public function generatePayvesselAccount($id, $fname, $lname, $phone, $email)
		{

			//Get Authorization Data
			$url = 'https://api.payvessel.com/api/external/request/customerReservedAccount/';

			$dbh = $this->connect();

			//Get API Details
			$d = $this->getApiConfiguration();
			$payvesselStatus = $this->getConfigValue($d, "payvesselStatus");
			$payvesselApiKey = $this->getConfigValue($d, "payvesselApiKey");
			$payvesselSecret = $this->getConfigValue($d, "payvesselSecret");
			$payvesselBusinessId = $this->getConfigValue($d, "payvesselBusinessId");

			$fname = str_replace(" ", "", $fname);
			$fname = trim($fname);
			$lname = str_replace(" ", "", $lname);
			$lname = trim($lname);
			$phone = trim($phone);
			$email = str_replace(" ", "", $email);

			//Get Token

			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => '{
                        "email":"' . $email . '",
                        "name":"' . $fname . ' ' . $lname . '",
                        "phoneNumber":"' . $phone . '",
                        "bankcode":["120001"],
                        "businessid":"' . $payvesselBusinessId . '"
                    
                    }',
				CURLOPT_HTTPHEADER => array(
					"api-key:$payvesselApiKey",
					"api-secret:Bearer $payvesselSecret",
					"Content-Type:application/json"
				),
			));


			$result = curl_exec($ch);
			curl_close($ch);
			$value = json_decode($result);

			file_put_contents("payversal_log.txt", $result);

			//Check And Save Account Details
			if (isset($value->banks[0]->accountNumber)) {
				$accountNumber = $value->banks[0]->accountNumber;

				//Save Account Number

				$dbh = $this->connect();
				$c = "UPDATE subscribers SET sPayvesselBank=:pb WHERE sId=$id";
				$queryC = $dbh->prepare($c);
				$queryC->bindParam(':pb', $accountNumber, PDO::PARAM_STR);
				$queryC->execute();
			}
		}


		//GET LIST Of BANKS
		public function getFullBankList()
		{

			//Get API Details
			$d = $this->getApiConfiguration();
			$a = $this->getSiteConfiguration();
			$monifyStatus = $this->getConfigValue($d, "monifyStatus");
			$monifyApi = $this->getConfigValue($d, "monifyApi");
			$monifySecrete = $this->getConfigValue($d, "monifySecrete");
			$monifyContract = $this->getConfigValue($d, "monifyContract");
			$adminEmail = $a->email;

			$accessKey = $monifyApi . ":" . $monifySecrete;
			$apiKey = base64_encode($accessKey);

			//Get Authorization Data
			$url = 'https://api.monnify.com/api/v1/auth/login';
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Basic {$apiKey}",
					"Content-Type: application/json"
				),
			));


			$json = curl_exec($ch);
			$result = json_decode($json);
			curl_close($ch);

			$accessToken = $result->responseBody->accessToken;

			//Get Authorization Data
			$url2 = 'https://api.monnify.com/api/v1/banks';
			$ch2 = curl_init();
			curl_setopt_array($ch2, array(
				CURLOPT_URL => $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer {$accessToken}",
					"Content-Type: application/json"
				),
			));


			$json2 = curl_exec($ch2);
			$result2 = json_decode($json2);
			curl_close($ch2);

			return $result2;
		}

		//Verify Bank Account Details
		public function verifyBankAccount($bankcode, $accountno)
		{

			//Get API Details
			$d = $this->getApiConfiguration();
			$a = $this->getSiteConfiguration();
			$monifyStatus = $this->getConfigValue($d, "monifyStatus");
			$monifyApi = $this->getConfigValue($d, "monifyApi");
			$monifySecrete = $this->getConfigValue($d, "monifySecrete");
			$monifyContract = $this->getConfigValue($d, "monifyContract");
			$adminEmail = $a->email;

			$accessKey = $monifyApi . ":" . $monifySecrete;
			$apiKey = base64_encode($accessKey);

			//Get Authorization Data
			$url = 'https://api.monnify.com/api/v1/auth/login';
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Basic {$apiKey}",
					"Content-Type: application/json"
				),
			));


			$json = curl_exec($ch);
			$result = json_decode($json);
			curl_close($ch);

			$accessToken = $result->responseBody->accessToken;

			//Get Authorization Data
			$url2 = 'https://api.monnify.com/api/v1/disbursements/account/validate?accountNumber=' . $accountno . '&bankCode=' . $bankcode;
			$ch2 = curl_init();
			curl_setopt_array($ch2, array(
				CURLOPT_URL => $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer {$accessToken}",
					"Content-Type: application/json"
				),
			));


			$json2 = curl_exec($ch2);
			$result2 = json_decode($json2);
			curl_close($ch2);

			return $result2;
		}

		//Create Kuda Virtual Bank Account
		public function generateKudaAccount($id, $fname, $lname, $phone, $email, $kudaApi, $kudaEmail)
		{

			//Get Authorization Data
			$url = 'https://kuda-openapi.kuda.com/v2.1/Account/GetToken/';
			$url2 = "https://kuda-openapi.kuda.com/v2.1/";

			//Get Token

			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => '{
											"email": "' . $kudaEmail . '",
											"apiKey": "' . $kudaApi . '"
									}',
				CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json",
				),
			));


			$result = curl_exec($ch);
			curl_close($ch);



			$accessToken = $result;
			$ref = "REQ_" . uniqid() . rand(1000, 9000);

			//Check Is User Have Middle Name
			$secondname = explode(" ", $lname);

			if (isset($secondname[0])): $lname = $secondname[0];
			endif;
			if (isset($secondname[1])): $mname = $secondname[1];
			else: $mname = "";
			endif;
			$fname = str_replace(" ", "", $fname);
			$lname = str_replace(" ", "", $lname);
			$mname = str_replace(" ", "", $mname);
			$fname = trim($fname);
			$lname = trim($lname);
			$mname = trim($mname);
			$phone = trim($phone);
			$email = str_replace(" ", "", $email);

			//Request Account Creation
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL =>  $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>
				'{
                                    		"ServiceType":"ADMIN_CREATE_VIRTUAL_ACCOUNT",
                                    		"RequestRef":"' . $ref . '",
                                    		"Data":
                                    			{
                                    				"email": "' . $email . '",
                                    				"phoneNumber": "' . $phone . '",
                                    				"lastName": "' . $lname . '",
                                    				"firstName": "' . $fname . '",
                                    				"middleName": "' . $mname . '",
                                                    "businessName": "",
                                    				"trackingReference": "' . $email . '"
                                    			}
                                    }
                                ',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer " . $accessToken,
					"Content-Type: application/json"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$value = json_decode($response);


			//Check And Save Account Details
			if (isset($value->data->accountNumber)) {
				$accountNumber = $value->data->accountNumber;

				//Save Account Number

				$dbh = $this->connect();
				$c = "UPDATE subscribers SET sKudaBank=:kb WHERE sId=$id";
				$queryC = $dbh->prepare($c);
				$queryC->bindParam(':kb', $accountNumber, PDO::PARAM_STR);
				$queryC->execute();
			}
		}

		//Complete Kuda Funding And Withdraw Funds From Virtual Account To Main Admin Wallet
		public function completeKudaFundingByWithdrawal($amount, $useremail)
		{

			$dbh = $this->connect();

			//Get API Details
			$d = $this->getApiConfiguration();
			$kudaStatus = $this->getConfigValue($d, "kudaStatus");
			$kudaEmail = $this->getConfigValue($d, "kudaEmail");
			$kudaApi = $this->getConfigValue($d, "kudaApi");

			//Get Authorization Data
			$url = 'https://kuda-openapi.kuda.com/v2.1/Account/GetToken/';
			$url2 = "https://kuda-openapi.kuda.com/v2.1/";

			//Get Token

			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => '{
											"email": "' . $kudaEmail . '",
											"apiKey": "' . $kudaApi . '"
									}',
				CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json",
				),
			));


			$result = curl_exec($ch);
			curl_close($ch);



			$accessToken = $result;
			$ref = "REQ_" . uniqid() . rand(1000, 9000);

			//Request Account Creation
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL =>  $url2,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS =>
				'{
                                    		"ServiceType":"WITHDRAW_VIRTUAL_ACCOUNT",
                                    		"RequestRef":"' . $ref . '",
                                    		"Data":
                                    			{
                                    				"trackingReference": "' . $useremail . '",
                                    				"amount": "' . $amount . '",
                                    				"narration": "Virtual Account Withdrawal",
                                    				"ClientFeeCharge": 0
                                    			}
                                    }
                                ',
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer " . $accessToken,
					"Content-Type: application/json"
				),
			));

			$response = curl_exec($curl);
			curl_close($curl);
			$value = json_decode($response);

			return $value;
		}
	}

	?>