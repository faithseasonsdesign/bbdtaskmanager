<?php

	//Author::Rebaone Faith Matlaba
	//connection variables
	$hostname = "localhost";
	$dbUsername = "root";
	$dbPassword = "";
	$dbName = "bbdtaskmanager";
	
	//connection string
	$connection = mysqli_connect($hostname,$dbUsername,$dbPassword,$dbName);
	
	//check connection
	/*
	if($connection){
		echo("<script>alert('connection to the server and database established successfuly')</script>");
	}
	else{
		echo("<script>alert('failed to establish the connection to the server and database')</script>");
	}
	*/

?>