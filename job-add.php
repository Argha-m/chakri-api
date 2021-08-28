<?php 
$conn = mysqli_connect('localhost',"root","","chakri_d72");

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    
    $c_id = $_REQUEST["c_id"];
    $j_name = $_REQUEST["j_name"];
    $j_des = $_REQUEST["j_des"];    
    $j_location = $_REQUEST["j_location"];
    $j_country = $_REQUEST["j_country"];


    $uploads_dir = 'files/';

    $pname = $uploads_dir.rand(1000,10000)."-".$_FILES["file"]["name"];

    $tname1 = $_FILES["file"]["tmp_name"];

    move_uploaded_file($tname1, $pname);


    $query = "INSERT INTO  job_tbl ( categories_id , job_name , job_description , job_location , job_country , job_img )

             VALUES('$c_id','$j_name','$j_des','$j_location','$j_country','$pname')";

    $result = $conn->query($query);

    if ($result == 1)
    {
        $data["message"] = "data saved successfully";
        $data["status"] = "Ok";
    }
    else
    {
        $data["message"] = "data not saved successfully";
        $data["status"] = "error";    
    }
		
	

} else {
    $data["message"] = "Format not supported";
    $data["status"] = "error";    
}
echo json_encode($data);
?>


