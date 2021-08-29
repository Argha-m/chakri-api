<?php 
// $con=new mysqli('localhost','root','');
// mysqli_select_db($con,'chakri_d72');

require_once("db.php");       
     
    if(isset($_GET['username']) && $_GET['username'] !='' && isset($_GET['password']) && $_GET['password'] !='')
    {

                    $user = $_GET["username"];
                    $pass = $_GET["password"];
                
                
                $getData="SELECT * FROM user_tbl";

                $result=mysqli_query($con,$getData);

                $userId = "";

                 while($r <= mysqli_fetch_row($result)){

                    $userId = $r[0];
                 }

                 if($result -> num_rows > 0){
                     $resp["status"]="1";
                     $resp["userId"]="$userId";
                     $resp["message"]="Login Successfully";
                 } 
                 else{
                     $resp['status']="-2";
                     $resp['message']="Enter correct Username and Password";
                 }

    }
    else{
            $resp['status']="-2";
            $resp['message']="Enter correct username";
    }

    header('content-type: application/json');

    $response["response"]=$resp;

    echo json_encode($response);
?>