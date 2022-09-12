<?php

	session_start();
	require("connection.php");
	
	$linkCondition = $_SESSION['linkCondition'];
	$dashboardLinkCondition = $_SESSION['dashboardLinkCondition'];
	
	//sanitize start
	function protected_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	//sanitize ended
	
	$taskEmail = $_SESSION['editEmail2'];
	$taskId = $_SESSION['edit_task_id'];
	//access click task information
	$taskTbl = "tasks";
	$sql = "SELECT * FROM $taskTbl WHERE assignedUserEmail='$taskEmail' AND taskId ='$taskId';";
	$results = mysqli_query($connection,$sql);
	if(mysqli_num_rows($results)>0){
		while($row = mysqli_fetch_assoc($results)){
			$taskTitle = $row['taskTitle'];
			$taskDescription = $row['taskDescription'];
			$assignedTo = $row['assignedUser'];
			$taskDepartment = $row['taskDepartment'];
			$taskStatus = $row['taskStatus'];
			
		}
	}
	
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
	
	<body style="background-color:#e9ecef">
	
		<!--coding for navigation menu start here-->
		<div class="nav-wrapper  bg-dark text-white">
			<div class="container nav-child-wrapper d-flex align-items-center">
				<div class="logo-wrapper text-center">
					<li class="nav-link">
						<a href="#">
							<img src="images/logo.png" height="180" class=" logo"/>
						</a>
					</li>
				</div>
				
				<div class="list-items-wrapper text-center container d-none d-sm-none d-md-flex justify-content-end">
					
					<li class="nav-link">
						<a href="<?php echo($dashboardLinkCondition) ?>" class="text-white">
							Dashboard
						</a>
					</li>
					<li class="nav-link <?php echo($linkCondition) ?>">
						<a href="add_department.php" class="text-white">
							Departments
						</a>
					</li>
					<li class="nav-link <?php echo($linkCondition) ?> ">
						<a href="add_employee.php" class="text-white">
							Employees
						</a>
					</li>
					<li class="nav-link <?php echo($linkCondition) ?> ">
						<a href="add_task.php" class="text-white">
							Tasks
						</a>
					</li>
					
				</div>
				
				<div class="nav-btn-wrapper container d-flex d-sm-flex d-md-none justify-content-end">
					<li id="nav-button" class="nav-list-items menu-btn">
						<i class="fa fa-bars fa-2x"></i>
					</li>
				</div>
			</div>
		</div>
		<!--coding for navigation menu ended here-->
		
		<!--coding for the dashboard start here-->
		<div class="description-wrapper">
			<div class="description-child-wrapper py-5 container-fluid" style="max-width:1600px;padding:20px">
				<div style="background-color:white" class="view-description-wrapper py-5 row">
					<div class="col-12 py-4">
						<h2 class="description_caption text-dark">
							Full task information
						</h2>
					</div>
					<div class="col-12">
						<h5 class="text-dark task_description">
							Task Description <br><?php echo($taskDescription) ?>
						<h5>
						<h5 class="text-dark assigned_to">
							Assigned To : <?php echo($assignedTo) ?>
						</h5>
						<?php
							$status_prop = "";
							if($taskStatus == "complete"){
								$status_prop = "text-success";
							}
							else{
								$status_prop = "text-danger";
							}
						?>
						<h5 class="text-dark">
							Task Status : 
							<span class="<?php echo($status_prop) ?>"> 
							<?php echo($taskStatus) ?> 
							</span>
						</h5>
						<h5 class="text-dark">
							Task Department : <?php echo($taskStatus) ?>
						</h5>
					</div>
				</div>
				
				<!--coding for the task list table start-->
				
				<!--coding for the task list table ended-->
			</div>
		</div>
		<!--coding for the dashboard ended here-->
		
		
		<!--tablets and small devices navigation list start-->
		<div id="sm-nav-list-wrapper" class="bg-dark py-5 text-white">
			<ul id="sm-nav-list" class="text-white text-center">
				<li class="nav-link">
					<a href="<?php echo($dashboardLinkCondition) ?>" class="text-white">
						Dashboard
					</a>
				</li>
				<li class="nav-link <?php echo($linkCondition) ?>">
					<a href="add_department.php" class="text-white">
						Departments
					</a>
				</li>
				<li class="nav-link <?php echo($linkCondition) ?> ">
					<a href="add_employee.php" class="text-white">
						Employees
					</a>
				</li>
				<li class="nav-link <?php echo($linkCondition) ?> ">
					<a href="add_task.php" class="text-white">
						Tasks
					</a>
				</li>
			</ul>
		</div>
		<!--tablets and small devices navigation list end-->


		<style>
			#sm-nav-list-wrapper{
				position:absolute;
				top:20vh;
				left:0;
				right:0;
				height:50vh;
				display:none;
			}
		</style>
		
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/style.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

</html>