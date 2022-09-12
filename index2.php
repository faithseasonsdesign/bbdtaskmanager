<?php

	session_start();
	require("connection.php");

?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/all.min.css" rel="stylesheet"/>
		<link href="css/style.css" rel="stylesheet"/>
		<title>BBD Employee Task Manager</title>
	</head>
	
	<body>
	
		<!--coding for navigation menu start here-->
		<div class="nav-wrapper  bg-dark text-white">
			<div class="container nav-child-wrapper d-flex align-items-center">
				<div class="list-items-wrapper text-center d-flex">
					<li class="nav-link">
						<a href="#" class="text-white">
							Dashboard
						</a>
					</li>
					<li class="nav-link">
						<a href="#" class="text-white">
							Dapartments
						</a>
					</li>
					<li class="nav-link">
						<a href="#" class="text-white">
							Employees
						</a>
					</li>
					<li class="nav-link">
						<a href="#" class="text-white">
							Tasks
						</a>
					</li>
				</div>
				
				<div class="nav-btn-wrapper container d-md-none justify-content-end">
					<li class="nav-list-items menu-btn">
						<i class="fa fa-bars fa-2x"></i>
					</li>
				</div>
			</div>
		</div>
		<!--coding for navigation menu ended here-->
		
		<!--coding for the dashboard start here-->
		
		
		
		
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/style.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

</html>