<?php

session_start();

/********************DB CONNECTION**************/

$con=mysqli_connect('localhost',"root","","chakri_d72");

/*****************CHECK CONNECTION**************/
if (mysqli_connect_errno()){
	
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  
}
  
?>