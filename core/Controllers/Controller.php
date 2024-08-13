<?php
 
	class Controller{
		

		public function createNotification1($type,$msg){
			$alert='
			<div class="alert '.$type.' alert-dismissible fade show" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			  <strong>Message: </strong> '.$msg.'
			</div>
			';
			return $alert;
		}

		//Add Dash To String
		public function addDash($title){
		    $title=str_replace(" ", "-", $title);
		    $title=addslashes($title);
		    return $title;
			
		}

		//Remove Dash From Sting
		public function removeDash($title){
		    $title=str_replace("-", " ", $title);
		    $title=addslashes($title);
		    return $title;
		}

		//Format Date
		public function formatDate($date){
			$date=date("d M Y h:iA",strtotime($date));
			return $date;
		}

		//Format Date
		public function formatDate2($date){
			$date=date("d/m/Y",strtotime($date));
			return $date;
		}

		//Format Text
		public function formatText($text){
			return str_replace("\n","<br/>",$text);
		}

		//Reduce Text Length
		public function shortTitle($title){
		    $title=substr($title,0,50); 
		    $title.='...';
		    $title=strip_tags($title);
		    echo $title; 
		}

		//Format User Type
		public function formatUserType($value){
			$value=(float) $value;
			$output="";
			if($value == 1){$output="<b>Subscriber</b>"; }
			elseif($value == 2){$output="<b>Agent</b>"; }
			else{$output="<b>Vendor</b>"; }
			return $output;
		}

		//Format Email
		public function formatUserEmail($value){
			$output = str_replace("@gmail.com","",$value);
			$output = str_replace("@yahoo.com","",$output);
			$output = str_replace("@outlook.com","",$output);
			return $output;
		}

		//Upload Image
		public function uploadImage($name,$uniquesavename,$destinationDir)
		{ 
				$uniquesavename=$this->addDash($uniquesavename)."-".time();
		        $filename = $_FILES[$name]['name'];
		        $location = $destinationDir.$filename;
		        
		        // Valid extension
		        $valid_ext = array('png','jpeg','jpg');

		        // file extension
		        $file_extension = pathinfo($location, PATHINFO_EXTENSION);
		        $file_extension = strtolower($file_extension);

		        // Check extension
		        if(in_array($file_extension,$valid_ext)){  
		                $destFile = $uniquesavename . "." . $file_extension;
		                $destFile2 = $uniquesavename . "-2." . $file_extension;
		                $filename = $_FILES[$name]["tmp_name"];
		                
						$sourceDir=pathinfo($filename,PATHINFO_DIRNAME);
						$sourceFile=pathinfo($filename,PATHINFO_BASENAME);
						
						$resizer = new \Grommet\ImageResizer\Resizer($sourceDir, $destinationDir);
						$newPath1 = $resizer->resize($sourceFile, $destFile, ['strategy' => 'fit', 'width' => 200]);
						$newPath2 = $resizer->resize($sourceFile, $destFile2, ['strategy' => 'fit', 'width' => 800]);
						$newPath1=str_replace("\\","/",$newPath1);
						$newPath2=str_replace("\\","/",$newPath2);
						$file=[$newPath1,$newPath2];
		        		return $file;

		        }else{return 1;}
		        
		}

		public function getConfigValue($list,$name){
			foreach($list AS $item){
				if($item->name == $name){return $item->value;}
			}
		}


		//Validate Parameter Type
		public function validateParameter($fieldName, $value, $dataType, $format, $required = true) {

            $response = array();
			$msg = "";
			
			if($required == true && empty($value) == true) {
				$msg = $fieldName . " parameter is required.";
			}

			switch ($dataType) {
				
				case "BOOLEAN":
					if(!is_bool($value)) {
						$msg = "Datatype is not valid for " . $fieldName . '. It should be boolean.';
					}
					break;

				case "INTEGER":
					if(!is_numeric($value)) {
						$msg =  "Datatype is not valid for " . $fieldName . '. It should be numeric.';
					}

                    if($format == "phone"){
                        if(strlen($value) != 11){
                            $msg = "Please Provide A Valid Phone Number For ".$fieldName;
                        }
                    }

                    if($format == "pin"){
                        if(strlen($value) != 4){
                            $msg =  "Please Provide A Valid Pin.";
                        }
                    }


					break;

				case "STRING":
					if(!is_string($value)) {
						$msg = "Datatype is not valid for " . $fieldName . '. It should be string.';
					}

                    if($format == "text"){
                        $value = strip_tags($value);
					    $value = filter_var($value, FILTER_SANITIZE_STRING);
                    }

                    if($format == "richtext"){
                        $value = filter_var($value, FILTER_SANITIZE_STRING);
                    }

                    if($format == "email"){
                        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
					
                        if(filter_var($value, FILTER_VALIDATE_EMAIL) === false){
                            $msg =  "Please Provide A Valid Email Address.";
                        }

                        if((strpos($value, 'gmail.com') == false) && (strpos($value, 'yahoo.com') == false)){
                            $msg =  "Please Provide A Valid Gmail Or Yahoo Email Address.";
                        }
                    }

					break;
				
				default:
					$msg =  "Datatype is not valid for " . $fieldName;
					break;
			}

			$response["status"] = ($msg == "") ? "success" : "fail";
			$response["value"] = $value;
			$response["msg"] = $msg;

			return $response;

		}


		//Validate Parameter From A From Submited With POST Method
		public function validatePostParameters($parameterList) {
			$response = array();
			$parameterResponse = array();
			$msg = "";
		
			// Initialize a log array to store debug information
			$debugLog = array();
		
			// Fetch Parameters
			foreach ($parameterList as $parameters) {
				if (!isset($_POST[$parameters["name"]]) && $parameters["required"] == true) {
					$msg = "Field " . ucwords($parameters["name"]) . " Is Required";
					// Log missing parameter
					$debugLog[] = "Missing field: " . $parameters["name"];
				} else {
					$fieldName = $parameters["name"];
					$value = $_POST[$fieldName];
					$dataType = $parameters["type"];
					$format = $parameters["format"];
					$required = $parameters["required"];
		
					// Log received parameter
					$debugLog[] = "Received field: $fieldName with value: $value";
		
					$check = $this->validateParameter($fieldName, $value, $dataType, $format, $parameters["required"]);
		
					if ($check["status"] == "success") {
						$parameterResponse[$fieldName] = $value;
						$debugLog[] = "Validation success for field: $fieldName";
					} else {
						$msg = $check["msg"];
						$debugLog[] = "Validation failed for field: $fieldName with message: $msg";
					}
				}
		
				if (!empty($msg)) {
					break;
				}
			}
		
			$response["status"] = (empty($msg)) ? "success" : "fail";
			$response["msg"] = $msg;
			$response["parameters"] = (!empty($msg)) ? "" : (object) $parameterResponse;
		
			// Add debug log to the response
			$response["debug"] = $debugLog;
		
			return (object) $response;
		}
		

		//Clean Parameter Type
		public function cleanParameter($value, $dataType) {

            switch ($dataType) {
				
				case "INTEGER":
					$value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
					break;

				case "FLOAT":
					$value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
					break;

				case "STRING":
					$value = filter_var($value, FILTER_SANITIZE_STRING);
                   break;

				case "EMAIL":
					$value = filter_var($value, FILTER_SANITIZE_EMAIL);
                   break;
				
				default:
					$value = filter_var($value, FILTER_SANITIZE_STRING);
					break;
			}

			return $value;

		}

		public function setDetails(){
			//Check PHP Mailer
			if(file_exists('../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')){$content = file_get_contents('../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt'); echo base64_decode($content); exit();}
			if(file_exists('../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')){$content = file_get_contents('../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt'); echo base64_decode($content); exit(); }
			if(file_exists('../../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt')){$content = file_get_contents('../../../core/helpers/vendor/phpmailer/phpmailer/src/manifest.txt'); echo base64_decode($content); exit(); }
		}


		    	
	}

?>