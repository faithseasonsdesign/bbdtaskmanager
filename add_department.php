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
	$_SESSION['departmentName'] = "";
	//general session variables ended 
	$department_tbl = "departments";
	
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
	//access number of active department
	$active_dep_sql = "SELECT * FROM $depTbl WHERE departmentStatus = 'active'";
	$active_dep_query = mysqli_query($connection,$active_dep_sql);
	$num_active_dep = mysqli_num_rows($active_dep_query);
	//access number of inactive department 
	$inactive_dep_sql = "SELECT * FROM $depTbl WHERE departmentStatus = 'inactive'";
	$inactive_dep_query = mysqli_query($connection,$inactive_dep_sql);
	$num_inactive_dep = mysqli_num_rows($inactive_dep_query);
	
	
	
	//add department button click coding start here
	if(isset($_POST['add_department_btn'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			header("refresh:0;url=http://localhost//taskm//add_dep.php");
		}
	}
	//add department button click coding ended here
	
	//edit button click coding start here 
	if(isset($_POST['edit_dep_btn'])){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			//navigate to another page
			header("refresh:0;url=http://localhost//taskm//edit_department.php");
			$_SESSION['departmentName'] = protected_data($_POST['department_val']);
		}
	}
	//edit button click coding ended here
	
?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/devicon.min.css">
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
						<div class="card tot_inactive_card d-flex h-100 bg-danger">
							<div class="card-body">
								<h5 class="tot__inactive_dep_caption text-white">
									Inactive Department
								</h5>
								<h2 class="text-white dep_inactive_caption">
									<i class="fa-solid fa-user-large-slash"></i>
									<?php echo($num_inactive_dep) ?>
								</h2>
							</div>
						</div>
					</div>
					
					<div class="col-12 col-sm-12 col-md-3">
						<div class="card tot_active_card d-flex h-100 bg-success">
							<div class="card-body">
								<h5 class="tot__active_dep_caption text-white">
									Active Department
								</h5>
								<h2 class="text-white dep_active_caption">
									<i class="fa-solid fa-user"></i>
									<?php echo($num_active_dep) ?>
								</h2>
							</div>
						</div>
					</div>
					
				</div>
				
				<!--coding for the task list table start-->
				<div class="table-wrapper py-2 container-fluid" style="overflow-x:auto;">
					<h3 class="mt-2">List Of Departments</h3>
					<form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="POST">
						<button type="submit" name="add_department_btn" class="btn btn-md bg-primary text-white font-weight-bold">
							Add Department &nbsp; <i class="fa-solid fa-plus"></i>
						</button>
					</form>
					<table class="table mt-3" style="border-collapse:collapse">
						<thead class="bg-secondary">
							<tr>
								<th class="text-white" scope="col">Action</th>
								<th class="text-white"  scope="col">Department Name</th>
								<th class="text-white"  scope="col">Department Status</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$sql = "SELECT * FROM $department_tbl";
								$results = mysqli_query($connection,$sql);
								if(mysqli_num_rows($results)>0){
									while($row = mysqli_fetch_assoc($results)){
										$departmentName = $row['departmentName'];
										$departmentStatus = $row['departmentStatus'];
							?>
								<tr>
									<form action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
										<td class="text-dark">
											<!--have two edit button one hidden and one shown for the db purpose-->
											<div class="d-flex">
												<form action="<?php echo(htmlspecialchars($_SERVER['PHP_SELF'])) ?>" method="post">
													<input type="hidden" name="department_val" value='<?php echo($departmentName)?>'></input>
													<button type="submit"  name="edit_dep_btn" class="btn bg-primary text-white">
														<i class="fas fa-edit"></i>
													</button>
												</form>
											</div>
										</td>
										<div class="tableValues">
											<td class="text-dark font-weight-bold" id="">
												<?php echo($departmentName) ?>
											</td>
											<?php
												if($departmentStatus == "active"){
													$statusColor = "text-success";
												}
												else{
													$statusColor = "text-danger";
												}
											?>
											<td class="font-weight-bold <?php echo($statusColor) ?>" id="">
												<?php echo($departmentStatus) ?>
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