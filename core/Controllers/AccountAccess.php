<?php
	
	class AccountAccess extends Controller{

		protected $model;

		public function __construct(){
			$this->model=new Account;
		}

		public function verifyAdminLogin(){
			$parameterList = [
				["name" => "username", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "password", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "loginpin", "type" => "STRING", "format" => "text", "required" => false]
			];

			$response = $this->validatePostParameters($parameterList);
			
			if ($response->status == "success") :
				$result=$this->model->verifyAdminAccount($response->parameters->username,$response->parameters->password,$response->parameters->loginpin);
			else:
				return json_encode(["status" => "invalid","msg" => $response->msg]);
			endif;

			return $result;
		}
		    
 

		//Register/Create User Account
		public function registerUser(){
			
			$parameterList = [
				["name" => "fname", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "lname", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "email", "type" => "STRING", "format" => "email", "required" => true],
				["name" => "phone", "type" => "INTEGER", "format" => "phone", "required" => true],
				["name" => "state", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "account", "type" => "STRING", "format" => "text", "required" => true],
				["name" => "referal", "type" => "STRING", "format" => "text", "required" => false],
				["name" => "password", "type" => "STRING", "format" => "richtext", "required" => true],
				["name" => "transpin", "type" => "INTEGER", "format" => "pin", "required" => true]
			];

			$response = $this->validatePostParameters($parameterList);

			if ($response->status == "success") :

				$fname=$response->parameters->fname; $lname=$response->parameters->lname; $email=$response->parameters->email; $phone=$response->parameters->phone;
				$state=$response->parameters->state; $account=$response->parameters->account; $referal=$response->parameters->referal; $transpin=$response->parameters->transpin;
				$password=$response->parameters->password;

				//Check If Phone Number Match Password
				if($phone == $password){return json_encode(["status" => "fail","msg" => "Phone Number Can't Be Used As Password"]);}
				if($password == "12345678"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Password"]);}
				if($password == "123456789"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Password"]);}
				if($transpin == "12345"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Pin"]);}
				if($transpin == "00000"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Pin"]);}
				if($transpin == "00112"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Pin"]);}
				if($transpin == "01234"){return json_encode(["status" => "fail","msg" => "Please Set A More Secured Pin"]);}

				//Register User

				$check=$this->model->registerUser($fname,$lname,$email,$phone,$password,$state,$account,$referal,$transpin);
				if($check->status == "success"){$result = json_encode(["status" => "success", "msg" => $check->msg]);}
				else{ $result = json_encode(["status" => "error", "msg" => $check->msg]); }

			else:
				return json_encode(["status" => "fail","msg" => $response->msg]);
			endif;

			return $result;

		}

		//Login User Account
		public function loginUser(){
			
			$parameterList = [
				["name" => "phone", "type" => "INTEGER", "format" => "phone", "required" => true],
				["name" => "password", "type" => "STRING", "format" => "richtext", "required" => true]
			];

			$response = $this->validatePostParameters($parameterList);

			if ($response->status == "success") :
				$check=$this->model->loginUser($response->parameters->phone,$response->parameters->password);
				if($check->status == "success"){$result = json_encode(["status" => "success", "msg" => $check->msg]);}
				else{ $result = json_encode(["status" => "error", "msg" => $check->msg]); }
			else:
				return json_encode(["status" => "fail","msg" => $response->msg]);
			endif;

			return $result;

		}

		//Recover User Account
		public function recoverUserLogin(){
			extract($_POST);
			$email=strip_tags($email);
			$email=$this->cleanParameter($email, "EMAIL");
			$check=$this->model->recoverUserLogin($email);
			return $check;
		}

		//Recover User Account
		public function verifyRecoveryCode(){
			extract($_POST);
			$email=strip_tags($email); $email = $this->cleanParameter($email, "EMAIL");
			$code=strip_tags($code); $code = $this->cleanParameter($code, "INTEGER");
			$check=$this->model->verifyRecoveryCode($email,$code);
			return $check;
		}

		//Recover Seller Account
		public function updateUserKey(){
			extract($_POST);
			
			$email=strip_tags($email); $email = $this->cleanParameter($email, "EMAIL");
			$code=strip_tags($code); $code = $this->cleanParameter($code, "INTEGER");
			$password=strip_tags($password); $password = $this->cleanParameter($password, "STRING");

			$check=$this->model->updateUserKey($email,$code,$password);
			return $check;
		}

	}

?>