<?php

require_once __DIR__ . '/config.php';

class API {

    function Select(){
        $db = new connect;
        $users = array();
        
        $data = $db->prepare('SELECT job_categories.categories_name,job_tbl.* FROM job_categories INNER JOIN job_tbl ON job_categories.categories_id = job_tbl.categories_id');
        $data->execute();

        while($OutputData = $data->fetch(PDO::FETCH_ASSOC)){

            $users[$OutputData['job_id']] = array(
                'jobId'=>$OutputData['job_id'],
                'jobCatergories'=>$OutputData['categories_name'],
                'jobName'=>$OutputData['job_name'],
                'jobDescription'=>$OutputData['job_description'],
                'jobLocation'=>$OutputData['job_location'],
                'jobCountry'=>$OutputData['job_country'],
                'jobImage'=>$OutputData['job_img']
                

            );
        }
        if($users != null){
            return json_encode(["status"=>200, "message"=>"Successfull", "data"=>$users]);
        } else {
            return json_encode(["status"=>500, "message"=>"No data found.", "data"=>null]);
        }
    }

}

$API = new API;
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, apikey');
$seceretKey = '32Xhsdf7asd';
$headers = getallheaders();
if(isset($headers['apikey'])){
    $api_key = $headers['apikey'];
    if($api_key != $seceretKey) {
        echo json_encode(["status"=>403, "message"=>"Authorization faild1"]);
        exit;
    } else {
        echo $API->Select();
    }
} else {
    echo json_encode(["status"=>403, "message"=>"Authorization faild2"]);
}
?>