<?php
	header('Content-Type: application/json');
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: POST");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, apiKey");
	$seceretKey = '32Xhsdf7asd';
	$headers = getallheaders();
 
	//DB connt file
	include_once 'config1.php';
 
	$database = new Database();
	$conn = $database->getConnection();
	
	//Request data input variable
	$data = json_decode(file_get_contents("php://input"));
	
	//Output variable
	$returnData = [];
	
	// Check if it's a post method
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		
		$returnData = array(
			"status" => 404,
			"message" => "Page Not Found!"
		);
		
	}
	else{
		// Check if comes with proper authentication 
		if (isset($headers['apiKey']) && $headers['apiKey'] === $seceretKey) {
			
			//check required value is not empty or wrong Key
			if( !isset($data->name) || 
			!isset($data->email) || 
			!isset($data->password) || 
			empty(trim($data->name)) || 
			empty(trim($data->email)) || 
			empty(trim($data->password)) || 
			empty(trim($data->roleType)) ){
				
				$returnData = array(
					"status" => 422,
					"message" => "Please Fill in all Required Fields!"
				);
				
			}
			// If everything goes right
			else {
				$name = trim($data->name);
				$user_name = explode (" ", $name)[0];
				$email = trim($data->email);
				$password = trim($data->password);
				$roleType = trim($data->roleType);
				
				try{
					$check_email = "SELECT `email` FROM `user_tbl` WHERE `email`=:email";
					$check_email_stmt = $conn->prepare($check_email);
					$check_email_stmt->bindValue(':email', $email,PDO::PARAM_STR);
					$check_email_stmt->execute();
					
					//If email already exist
					if($check_email_stmt->rowCount()) {
						$returnData = array(
							"status" => 422,
							"message" => "This E-mail already in use!"
						);
					} 
					// Or add new User to DB
					else {
						//Insert data
						$insert_query = "INSERT INTO `user_tbl`(`username`, `name`, `email`, `password`, `roleid`) VALUES(:username, :name, :email, :password, :roleid)";
					
						$insert_stmt = $conn->prepare($insert_query);
						
						// DATA BINDING
						$insert_stmt->bindValue(':username', htmlspecialchars(strip_tags($user_name)),PDO::PARAM_STR);
						$insert_stmt->bindValue(':name', htmlspecialchars(strip_tags($name)),PDO::PARAM_STR);
						$insert_stmt->bindValue(':email', $email,PDO::PARAM_STR);
						$insert_stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT),PDO::PARAM_STR);
						$insert_stmt->bindValue(':roleid', htmlspecialchars(strip_tags($roleType)),PDO::PARAM_STR);
						
						$insert_stmt->execute();
						
						$returnData = array(
							"status" => 200,
							"message" => "You have successfully registered."
						);
						
					}
					
				}
				//If any server error occurs
				catch(PDOException $exception){
					$returnData = array(
						"status" => 500,
						"message" => $exception->getMessage()
					);
				}
			}
		}
		// If authentication failed
		else {
			$returnData = array(
				"status" => 403,
				"message" => "Authorization faild!"
			);
		}
	}
	
	//Return ultimate value
	echo json_encode($returnData);
?>