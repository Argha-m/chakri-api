<?php 
$conn = mysqli_connect('localhost',"root","","chakri_d72");

if ($_SERVER["REQUEST_METHOD"] === 'POST')
{
    $categoriesname = $_REQUEST["categoriesname"];
    $categoriesdescription = $_REQUEST["categoriesdescription"];
    


    $query = "INSERT INTO  job_categories (categories_name,categories_description)
    VALUES('$categoriesname','$categoriesdescription')";

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
}
else
{
    $data["message"] = "Format not supported";
    $data["status"] = "error";    
}
    echo json_encode($data);
?>