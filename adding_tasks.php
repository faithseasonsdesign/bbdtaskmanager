<?php

	session_start();
	require("connection.php");
	
	$_SESSION['assignedEmployee'] = "";
	$_SESSION['assignedDepartment'] = "";
	$selected_department_session = "";
	//sanitize start
	function protected_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	//sanitize ended
	
	//database information start here
	$taskTbl = "tasks";
	$employeeTbl = "employees";
	//database information ended here
	
	$general_error = "";
	$error_caption_prop = "";
	
	//task variables will start here
	$task_title = "" ; $task_title_error = "";
	$task_description = "" ; $task_description_error = "";
	$task_department = "" ; 
	$task_assigned_to = "" ; $task_assigned_to_error = "";
	$task_status = "" ;
	$task_date = "" ; 
	//task variables will enddd here 
	
	if(isset($_POST['add_task_btn'])){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//check if the inputs field are empty or not
			$task_title = protected_data($_POST['task_title_input']);
			$task_description = protected_data($_POST['task_description_input']);
			$task_assigned_to = "";//name here stored from when getting
			$task_assigned_to_email = protected_data($_POST['task_assigned_to_input']);
			$task_department = protected_data($_POST['department_name_input']);
			$task_status = protected_data($_POST['task_status_input']);
			$task_date = protected_data($_POST['task_date_input']);
			
			//($task_assigned_to_email);
			
			//access the name of the person assigned a task 
			$emp_tbl = "employees";
			$name_sql = "SELECT * FROM $employeeTbl WHERE empEmail = '$task_assigned_to_email'";
			$name_query = mysqli_query($connection,$name_sql);
			$name_exist = mysqli_num_rows($name_query);
			if($name_exist > 0){
				while($name = mysqli_fetch_assoc($name_query)){
					$task_assigned_to = $name['empFullname'];
				}
			}
			//echo($task_assigned_to);
			
			if(empty($task_title) || empty($task_description)){
				$error_section_prop = "bg-danger py-2 text-white";
				$error_caption_prop = "text-danger font-weight-bold";
				$general_error = "failed to assign assignment to an employee";
				//one of the inputs field is empty check which one
				//check task title
				if(empty($task_title)){
					$task_title_error = "you cannot leave the task title input field empty";
				}
				//check task description
				if(empty($task_description)){
					$task_description_error = "you cannot leave the task description input field empty";
				}
			}
			else{
				//all the input fields are filled with data you can add or assign the task 
				//assign task to employee
				$assign_task_sql = "INSERT INTO $taskTbl(taskTitle,taskDescription,taskDepartment,assignedUser,taskStatus,taskDate,assignedUserEmail) VALUES('$task_title','$task_description','$task_department','$task_assigned_to','$task_status','$task_date','$task_assigned_to_email');";
				$assign_task_query = mysqli_query($connection,$assign_task_sql);
				//check if we were able to assign task successfuly started
				if($assign_task_query){
					//successfull assigned task now redirect to the other page
					header("refresh:2;url=http://localhost//taskm//add_task.php");
					$general_error = " Assigned task successfuly redirection you in 2 seconds ";
					$error_section_prop = "bg-success text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center";
				}
				else{
					//failed to assign task
					$general_error = "failed to assign task to employee";
					$error_section_prop = "bg-danger text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center";					
				}
				//check if we were able to assign task successfuly code ended
			}
			
		}
	}
	
?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
		<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
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
		
		<div class="login-wrapper d-flex justify-content-center">
			<div class="login-child-wrapper container py-2 mt-5" style="background-color:white">
				<form class="add-dep-form d-flex row py-5 text-center" style="background-color:white" action="<?php echo($_SERVER['PHP_SELF'])?>" method="post">
					<!--section that display an error code start here-->
					<div class="col-12 <?php echo($error_section_prop) ?>">
						<p class="general_error">
							<?php echo($general_error) ?>
						</p>
					</div>
					<!--section that display an error code ended here-->
					<div class="col-12 py-4">
						<h2 class="add_task_caption text-dark">
							Add A Task 
						</h2>
					</div>
					<div class="col-12 ">
						<h5 class="text-dark">
							Task Title
						</h5>
						<input  type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="task_title_input" placeholder="eg. Design a website for BBD" value="<?php echo($task_title) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($task_title_error) ?>
						</p>
					</div>
					<div class="col-12 col-sm-12 col-md-12">
						<h5>Task Description</h5>
						<textarea id="summernote" name="task_description_input" class="col-12 col-sm-12 col-md-4 text-left">
						
						</textarea>
						<p class="<?php echo($error_caption_prop) ?>" >
							<?php echo($task_description_error) ?>
						</p>
					</div>
					<div class="col-12">
						<div class="container  text-center">
							<h5>Assign Task To</h5>
							<select name="task_assigned_to_input" class="col-12 col-sm-12 col-md-4 py-2 font-weight-bold text-center">
								<?php
									$employeeTbl = "employees";
									$employee_sql = "SELECT * FROM  $employeeTbl;";
									$employee_query = mysqli_query($connection,$employee_sql);
									$employee_num = mysqli_num_rows($employee_query);
									if($employee_num > 0){
										while($employee = mysqli_fetch_assoc($employee_query)){
											$emp_name = $employee['empFullname'];
											$emp_email = $employee['empEmail'];
											$emp_department = $employee['empDepartment'];
								?>
									<option value="<?php echo($emp_email) ?>">
										<?php echo($emp_name . "(" . $emp_department . ")") ?>
									</option>
								<?php
										}
									}
								?>
							</select>
						</div>	
					</div>
					<div class="col-12">
						<div class="d-flex flex-column align-items-center">
							<h5>Select Department</h5>
							<select class="col-12 col-sm-12 col-md-4 py-2 text-center font-weight-bold" name="department_name_input">
								<?php
									$departmentTbl = "departments";
									$dep_sql = "SELECT * FROM $departmentTbl;";
									$dep_query = mysqli_query($connection,$dep_sql);
									$dep_num = mysqli_num_rows($dep_query);
									if($dep_num > 0){
										while($department = mysqli_fetch_assoc($dep_query)){
											$departmentName = $department['departmentName'];
								?>
									<option value="<?php echo($departmentName) ?>">
										<?php echo($departmentName) ?>
									</option>
								<?php
										}
									}
								?>
							</select>
						</div>	
					</div>
					<div style="" class="col-12 mt-1 d-flex justify-content-center flex-column align-items-center">
						<h5 class="text-dark">
							Task Status
						</h5>
						<select name="task_status_input" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4">
							<option value="pending">pending</option>
							<option value="complete">complete</option>
						</select>
					</div>
					
					<div class="col-12 d-flex justify-content-center flex-column align-items-center">
						<h5 class="text-dark">
							Task Date
						</h5>
						<input type="date" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="task_date_input" value=""></input>
						
					</div>
					<div class="col-12 mt-4 d-flex justify-content-center flex-column align-items-center">
						<form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="POST" class="col-12 d-flex justify-content-center">
							<button type="submit" name="add_task_btn" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
								Add Task
							</button>
						</form>
					</div>
				</form>
			</div>
		</div>
		
		
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
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
		<script>
			$('#summernote').summernote({
				height:200,
				focus:true
			});
		</script>
	</body>

</html>