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
	
	//general session variables start 
	$_SESSION['editEmail'] = '';
	$_SESSION['editEmail2'] = '';
	$_SESSION['edit_task_id'] = '';
	//general session variables ended 
	
	//access the number of departments
	$depTbl = "departments";
	$depSql = "SELECT * FROM $depTbl";
	$depQuery = mysqli_query($connection,$depSql);
	$numOfDep = mysqli_num_rows($depQuery);
	//access the number of employees
	$employeeTbl = "employees";
	$empSql = "SELECT * FROM $employeeTbl";
	$empQuery = mysqli_query($connection,$empSql);
	$numOfEmployees = mysqli_num_rows($empQuery);
	//access the number of pending tasks
	$taskTbl = "tasks";
	$pendingTasksSql = "SELECT taskStatus FROM $taskTbl WHERE taskStatus='pending';";
	$pendingTasksQuery = mysqli_query($connection,$pendingTasksSql);
	$numOfPendingTasks = mysqli_num_rows($pendingTasksQuery);
	//access the number of complete tasks
	$taskTbl = "tasks";
	$completeTasksSql = "SELECT taskStatus FROM $taskTbl WHERE taskStatus='complete';";
	$completeTasksQuery = mysqli_query($connection,$completeTasksSql);
	$numOfCompleteTasks = mysqli_num_rows($completeTasksQuery);
	
	
	//edit button click coding start here
	if(isset($_POST['editBtn'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			header("refresh:0;url=http://localhost//taskm//edit_task.php");
			$_SESSION['editEmail'] = protected_data($_POST['edit_button']);
			$_SESSION['edit_task_id'] = protected_data($_POST['edit_button_dash_id']);
		}
	}
	//edit button click coding ended here
	//view detailed button click code start here 
	if(isset($_POST['detailedBtn'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			header("refresh:0;url=http://localhost//taskm//detailed_task.php");
			$_SESSION['editEmail2'] = $_POST['view_detailed'];
			$_SESSION['edit_task_id'] = protected_data($_POST['edit_button_dash_id']);
		}
	}
	//view detailed button click code ended here
	
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
		<div class="dashboard-wrapper">
			<div class="dashboard-child-wrapper container py-5">
				<div class="dashboard-content row">
					
					<div class="col-12 col-sm-12 col-md-3">
						<div class="card tot_department-card d-flex h-100">
							<div class="card-body">
								<h5 class="tot_department_caption text-white">Total Departments</h5>
								<h2 class="text-white dep_num_caption">
									<i class="fa-solid fa-users-gear"></i>
									<?php echo($numOfDep) ?>
								</h2>
							</div>
						</div>
					</div>
					
					<div class="col-12 col-sm-12 col-md-3">
						<div class="card tot_employees_card d-flex h-100 bg-primary">
							<div class="card-body">
								<h5 class="tot_employees_caption text-white">
									Total Employees
								</h5>
								<h2 class="text-white dep_num_caption">
									<i class="fa-solid fa-user-tie"></i>
									<?php echo($numOfEmployees) ?>
								</h2>
							</div>
						</div>
					</div>
					
					<div class="col-12 col-sm-12 col-md-3">
						<div class="card pending_task_card d-flex h-100">
							<div class="card-body">
								<h5 class="pending_task_caption text-white">Pendings Tasks</h5>
								<h2 class="text-white pending_tasks_caption">
									<i class="fa-solid fa-clipboard-list"></i>
									<?php echo($numOfPendingTasks) ?>
								</h2>
							</div>
						</div>
					</div>
					
					<div class="col-12 col-sm-12 col-md-3">
						<div class="card complete_tasks_card d-flex h-100">
							<div class="card-body">
								<h5 class="complete_task_caption text-white">Complete Tasks</h5>
								<h2 class="text-white complete_task_caption">
									<i class="fa-solid fa-list-check"></i>
									<?php echo($numOfCompleteTasks) ?>
								</h2>
							</div>
						</div>
					</div>
					
				</div>
				
				<!--coding for the task list table start-->
				<div class="table-wrapper py-2 container-fluid" style="overflow-x:auto;">
					<h3 class="mt-2">List Of Tasks</h3>
					<table class="table mt-3" style="border-collapse:collapse">
						<thead class="bg-secondary">
							<tr>
								<th class="text-white" scope="col">Action</th>
								<th class="text-white"  scope="col">Assigned To</th>
								<th class="text-white"  scope="col">Title</th>
								<th class="text-white"  scope="col">Department</th>
								<th class="text-white"  scope="col">Status</th>
								<th class="text-white"  scope="col">Date</th>
								<th class="text-white" scope="col">View Details</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql = "SELECT * FROM $taskTbl";
								$results = mysqli_query($connection,$sql);
								if(mysqli_num_rows($results)>0){
									while($row = mysqli_fetch_assoc($results)){
										$taskTitle = $row['taskTitle'];
										$taskDepartment = $row['taskDepartment'];
										$assignedTo = $row['assignedUser'];
										$taskStatus = $row['taskStatus'];
										$taskDate = $row['taskDate'];
										$taskEmail = $row['assignedUserEmail'];
										$taskId = $row['taskId'];
							?>
								<tr>
									<form action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
										<td class="text-dark">
											<!--have two edit button one hidden and one shown for the db purpose-->
											<div class="d-flex">
												<form action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
													<input type="hidden" name="edit_button" value='<?php echo($taskEmail)?>'></input>
													<input type="hidden" name= "edit_button_dash_id" value="<?php echo($taskId) ?>"></input>
													<button type="submit"  name="editBtn" class="btn bg-primary text-white">
														<i class="fas fa-edit"></i>
													</button>
												</form>
											</div>
										</td>
										<div class="tableValues">
											<td class="text-dark font-weight-bold" id="">
												
												<?php echo($assignedTo) ?>
											</td>
											<td class="text-dark font-weight-bold" id="">
												<?php echo($taskTitle) ?>
											</td>
											<td class="text-dark font-weight-bold" id="">
												<?php echo($taskDepartment) ?>
											</td>
											<?php
												if($taskStatus=="complete"){
													$taskStatusColor = "text-success";
												}
												else{
													$taskStatusColor = "text-primary";
												}
											?>
											<td class="font-weight-bold <?php echo($taskStatusColor) ?>" id="">
												<?php echo($taskStatus) ?>
											</td>
											<td class="text-dark font-weight-bold" id="">
												<?php echo($taskDate) ?>
											</td>
											<td class="text-dark">
												<form action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
													<input type="hidden" value="<?php echo($taskEmail) ?>" name="view_detailed"></input>
													<input type="submit" class="btn btn-sm bg-danger text-white" value="View Details" name="detailedBtn" />
												</form>
											</td>
										</div>
										
									</form>
								</tr>
							<?php
									}
								}
							?>
							<h1 class="save"></h1>
						</tbody>
					</table>
				</div>
				<!--coding for the task list table ended-->
			</div>
		</div>
		<!--coding for the dashboard ended here-->
		
		
		<!--tablets and small devices navigation list start-->
		<div id="sm-nav-list-wrapper" class="bg-dark py-5 text-white">
			<ul id="sm-nav-list" class="text-white text-center">
				<li class="">
					<a href="dashboard.php" class="text-white">
						Dashboard
					</a>
				</li>
				<li class="">
					<a href="add_department.php" class="text-white">
						Departments
					</a>
				</li>
				<li class="">
					<a href="add_employee.php" class="text-white">
						Employees
					</a>
				</li>
				<li class="">
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