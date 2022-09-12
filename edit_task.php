<?php

	session_start();
	require("connection.php");
	
	//sanitize start
	function protected_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	//sanitize ended
	
	$taskEmail = $_SESSION['editEmail'];
	$taskId = $_SESSION['edit_task_id'];
	//access click task information
	$taskTbl = "tasks";
	$sql = "SELECT * FROM $taskTbl WHERE assignedUserEmail='$taskEmail' AND taskId = '$taskId';";
	$results = mysqli_query($connection,$sql);
	if(mysqli_num_rows($results)>0){
		while($row = mysqli_fetch_assoc($results)){
			$taskTitle = $row['taskTitle'];
			$taskDescription = $row['taskDescription'];
			$taskDepartment = $row['taskDepartment'];
			$assignedTo = $row['assignedUser'];
			$taskStatus = $row['taskStatus'];
			$taskDate = $row['taskDate'];
			$taskEmail = $row['assignedUserEmail'];
		}
	}
	
	//update button click code start
	$t_title = "" ; $t_title_error = "";
	$t_description = "" ; $t_description_error = "";
	//$t_employee = "" ; $t_employee_error = "";
	//$t_employee_email = "" ; $t_employee_email_error = "";
	$general_error = "";
	$error_section_prop = "";
	
	if(isset($_POST['update_btn'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$t_title = protected_data($_POST['task_title_input']);
			$t_description = protected_data($_POST['task_description_input']);
			$t_employee = protected_data($_POST['task_assign_input']);
			$t_employeeEmail = protected_data($_POST['task_assign_email_input']);
			$t_department = protected_data($_POST['task_department_input']);
			$t_date = protected_data($_POST['task_date_input']);
			$t_status = protected_data($_POST['task_status_input']);
			//check which is empty code start
			if(empty($t_title)|| empty($t_description)){
				//one of them is empty check which one
				$general_error  = "failed to update the task ";
				$error_section_prop = "bg-danger";
				
				$formDisplay = "d-block";
				$formColor = "bg-danger text-white font-weight-bold";
				
				if(empty($t_title)){
					$t_title_error = "You cannot leave the task title field empty";
				}
				if(empty($t_description)){
					$t_description_error = "You cannot leave the task description field empty";
				}
			}
			else{
				//all of them are filled with data
				$update_task_sql = "UPDATE $taskTbl SET taskTitle = '$t_title' , taskDescription = '$t_description' , taskDepartment = '$t_department' , assignedUser = '$assignedTo' , taskStatus = '$t_status' , taskDate = '$t_date' , assignedUserEmail = '$taskEmail' WHERE assignedUserEmail = '$taskEmail' AND taskId = '$taskId';";
				$update_task_query = mysqli_query($connection,$update_task_sql);
				if($update_task_query){
					header("refresh:2;url=http://localhost//taskm//dashboard.php");
					$general_error = "updated task information successfuly";
					$error_section_prop = "bg-success";
				}
				else{
					//failed to update the task 
					$general_error = "failed to update the task";
					$error_section_prop = "bg-danger";
				}
			}
			//check which is empty code ended
		}
	}
	//update button click code ended 
	
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
				<div class="logo-wrapper text-center">
					<li class="nav-link">
						<a href="#">
							<img src="images/logo.png" height="180" class=" logo"/>
						</a>
					</li>
				</div>
				
				<div class="list-items-wrapper text-center container d-none d-sm-none d-md-flex justify-content-end">
					<li class="nav-link">
						<a href="dashboard.php" class="text-white">
							Dashboard
						</a>
					</li>
					<li class="nav-link">
						<a href="add_department.php" class="text-white">
							Departments
						</a>
					</li>
					<li class="nav-link">
						<a href="add_employee.php" class="text-white">
							Employees
						</a>
					</li>
					<li class="nav-link">
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
		<div class="edit_task-wrapper">
			<div class="edit_task-child-wrapper py-5 container-fluid" style="max-width:1600px;padding:20px">
				<form style="background-color:white" action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post" class="edit_task_wrapper row d-flex py-5">
					<div class="col-12 col-sm-12 col-md-12 py-3 <?php echo($error_section_prop) ?>">
						<h6 class="text-white"><?php echo($general_error) ?></h6>
					</div>
					<div class="col-12 py-4">
						<h2 class="edit_task_caption text-dark">
							Edit Task
						</h2>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5>Task Title</h5>
						<textarea type="text" name="task_title_input" class="py-2 mt-2 text-center font-weight-bold col-12 col-sm-12 col-md-6" rows="3"><?php echo($taskTitle) ?></textarea>
						<h6 class="text-danger">
							<?php echo($t_title_error) ?>
						</h6>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Task Description</h5>
						<textarea type="text" name="task_description_input" class="py-2 mt-2 text-center font-weight-bold col-12 col-sm-12 col-md-6" rows="3"><?php echo($taskDescription) ?></textarea>
						<h6 class="text-danger">
							<?php echo($t_description_error) ?>
						</h6>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Task Department</h5>
						<select class="py-2 col-12 col-sm-12 col-md-6 font-weight-bold" name="task_department_input">
							<?php
								$departmentTbl = "departments";
								$departmentSql = "SELECT departmentName FROM $departmentTbl;";
								$departmentResults = mysqli_query($connection,$departmentSql);
								if(mysqli_num_rows($departmentResults)){
									while($department = mysqli_fetch_assoc($departmentResults)){
										$departmentName = $department['departmentName'];
							?>
								<option class="" value="<?php echo($departmentName) ?>">
									<?php echo($departmentName) ?>
								</option>
							<?php 
									}
								}
							?>
						</select>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Task Status</h5>
						<select class="py-2 col-12 col-sm-12 col-md-6 font-weight-bold" name="task_status_input">
							<option value="pending">pending</option>
							<option value="complete">complete</option>
						</select>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Employee Assigned To</h5>
						<input type="text" name="task_assign_input" value="<?php echo($assignedTo) ?>" class="py-2 mt-2 text-center font-weight-bold col-12 col-sm-12 col-md-6" readonly></input>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Employee email</h5>
						<input type="text" name="task_assign_email_input" value="<?php echo($taskEmail) ?>" class="py-2 mt-2 text-center font-weight-bold col-12 col-sm-12 col-md-6" readonly></input>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<h5 class="mt-2">Task Date</h5>
						<input type="date" name="task_date_input" value="<?php echo($taskDate) ?>" class="py-2 mt-2 text-center font-weight-bold col-12 col-sm-12 col-md-6"></input>
					</div>
					<div class="col-12 col-sm-12 col-md-6 d-flex align-items-end">
						<button type="submit" class="btn btn-md font-weight-bold mt-2 bg-success text-white" name="update_btn">
							Update Task
						</button>
					</div>
				</form>
				
				<!--coding for the task list table start-->
				
				<!--coding for the task list table ended-->
			</div>
		</div>
		<!--coding for the dashboard ended here-->
		
		<!--tablets and small devices navigation list start-->
		<div id="sm-nav-list-wrapper" class="bg-dark py-5 text-white">
			<ul id="sm-nav-list" class="text-white text-center">
				<li class="nav-link">
						<a href="dashboard.php" class="text-white">
							Dashboard
						</a>
					</li>
					<li class="nav-link">
						<a href="add_department.php" class="text-white">
							Departments
						</a>
					</li>
					<li class="nav-link">
						<a href="add_employee.php" class="text-white">
							Employees
						</a>
					</li>
					<li class="nav-link">
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