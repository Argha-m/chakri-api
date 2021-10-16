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
	//print_r($data);
	
	//Output variable
	$returnData = [];
	
	// Check if it's a post method
	if($_SERVER["REQUEST_METHOD"] != "POST"){
		
		$returnData = array(
			"status" => 404,
			"message" => "Page Not Found!"
		);
		
	} else {
		// Check if comes with proper authentication 
		if (isset($headers['apiKey']) && $headers['apiKey'] === $seceretKey) {
			
			if(!isset($data->email) 
			|| !isset($data->password)
			|| empty(trim($data->email))
			|| empty(trim($data->password))
			){
				$fields = ['fields' => ['email','password']];
				$returnData = array(
					"status" => 422,
					"message" => "Please Fill in all Required Fields!",
					$fields
				);
			} else {
				$email = trim($data->email);
				$password  = trim($data->password );
				
				try{
					$fetch_user_by_email = "SELECT * FROM `user_tbl` WHERE `email`=:email";
					$query_stmt = $conn->prepare($fetch_user_by_email);
					$query_stmt->bindValue(':email', $email, PDO::PARAM_STR);
					$query_stmt->execute();
					
					if($query_stmt->rowCount()){
						$row = $query_stmt->fetch(PDO::FETCH_ASSOC);
						$check_password = password_verify($password, $row['password']);
						$user_role = $row['roleid'];
						$fetch_role_by_id = "SELECT * FROM `user_role_tbl` WHERE roleid='".$row['roleid']."'";
						$role_stmt = $conn->prepare($fetch_role_by_id);
						// execute query
						$role_stmt->execute();
						
						if($role_stmt->rowCount()){
							$role_row = $role_stmt->fetch(PDO::FETCH_ASSOC);
							
							if($check_password){
								$returnData = array(
									"status" => 200,
									"message" => "Successfull!",
									"userName" => $row['username'],
									"name" => $row['name'],
									"role" => $role_row['roletype']
								);
							}else {
								$returnData = array(
									"status" => 422,
									"message" => "Invalid Password!"
								);
							}
						}
						
					} else {
						$returnData = array(
							"status" => 422,
							"message" => "User not avialable!"
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
			
		} else {
			$returnData = array(
				"status" => 403,
				"message" => "Authorization faild!"
			);
		}
	}
	
	//Return ultimate value
	echo json_encode($returnData);
?>