<?php
require_once __DIR__ . '/config.php';

class API {

    function Select(){
        $db = new connect;
        $job_cate = array();
        
        $data = $db->prepare('SELECT * FROM job_categories');
        $data->execute();

        while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){

            $job_cate[$OutputData['categories_id']] = array(
                'ID'=>$OutputData['categories_id'],
                'Job Name'=>$OutputData['categories_name'],
                'Job Description'=>$OutputData['categories_description']
                

            );
        }
        return json_encode($job_cate);
    }

}

$API = new API;
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, apiKey');
$seceretKey = '32Xhsdf7asd';
$headers = getallheaders();
if(isset($headers['apiKey'])){
	$api_key = $headers['apiKey'];
	if($api_key != $seceretKey) {
         echo json_encode(["status"=>403, "message"=>"Authorization faild"]);
         //403,'Authorization faild'; your logic
         exit;
    } else {
        echo $API->Select();
    }
} else {
     echo json_encode(["status"=>403, "message"=>"Authorization faild"]);
}



?>