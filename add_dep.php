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
	
	//database information start here
	$departmentTbl = "departments";
	//database information ended here
	
	$general_error = "";
	$error_caption_prop = "";
	$dep_name = ""; $deparment_name_error = "";
	$dep_status = "";
	
	//add button press start 
	if(isset($_POST['add_dep_button'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$dep_name = protected_data($_POST['dep_name_input']);
			$dep_status = protected_data($_POST['dep_status_input']);
			//checking if the inputs field are not empty code start
			if(empty($dep_name)|| empty($dep_status)){
				//one of the input field is empty check which one 
				$general_error = " failed to add the department one of the input field may be empty or department already exist ";
				$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
				$error_caption_prop = "text-danger font-weight-bold";
				//check department name
				if(empty($dep_name)){
					$deparment_name_error = " you cannot leave the department name input field empty ";
				}
				//check department status
				if(empty($dep_status)){
					$department_status_error = " you cannot leave the department status input field empty ";
				}
				//check which input field is empty coding ended 
			}
			else{
				//all the inputs field filled check if the department does not exist 
				$dep_exist_sql = "SELECT * FROM $departmentTbl WHERE departmentName = '$dep_name'";
				$dep_exist_query = mysqli_query($connection,$dep_exist_sql);
				$dep_exist = mysqli_num_rows($dep_exist_query);
				//check if department exist
				if($dep_exist>0){
					//deparment exist dont add the department
					$general_error = " the deparment already exist ";
					$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
					$error_caption_prop = "text-danger font-weight-bold";
				}else{
					//deparment does not exist add deparment
					$add_dep_sql = "INSERT INTO $departmentTbl(departmentName,departmentStatus) VALUES('$dep_name','$dep_status');";
					$add_dep_query = mysqli_query($connection,$add_dep_sql);
					//check if department was added successfuly coding ended here
					if($add_dep_query){
						//department was added succesfuly redirec the admin to the list of employees
						header("refresh:0;url=http://localhost//taskm//add_department.php");
						$general_error = " department was added successfuly redirecting you in 3 seconds ";
						$error_section_prop = "bg-success text-white d-flex justify-content-center align-items-center py-3 col-12";
						$error_section_caption = "text-white font-weight-bold";
					}
					else{
						//department was not added
						$general_error = " failed to add the department something is wrong on the technical side we will fix it ";
						$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
						$error_section_caption = "text-danger font-weight-bold";
					}
					//check if department was added succesfuly coding start here
				}
				//checking if deparments exist coding ended here
			}
			//checking if the inputs field are not empty code ended 
		}
	}
	//add button press ended 
	
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
		
		<div class="login-wrapper d-flex justify-content-center">
			<div class="login-child-wrapper container py-5">
				<form class="add-dep-form d-flex row py-5 text-center" style="background-color:white" action="<?php echo($_SERVER['PHP_SELF'])?>" method="post">
					<!--section that display an error code start here-->
					<div class="col-12 <?php echo($error_section_prop) ?>">
						<p class="general_error">
							<?php echo($general_error) ?>
						</p>
					</div>
					<!--section that display an error code ended here-->
					<div class="col-12 py-4">
						<h2 class="add_dep_caption text-dark">
							Add Department
						</h2>
					</div>
					<div class="col-12 ">
						<h5 class="text-dark">
							Department Name
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="dep_name_input" placeholder="Name Of Department" value="<?php echo($dep_name) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($deparment_name_error) ?>
						</p>
					</div>
					<div class="col-12">
						<h5 class="text-dark">
							Department Status
						</h5>
						<select name="dep_status_input" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4">
							<option value="active">active</option>
							<option value="inactive">inactive</option>
						</select>
					</div>
					<div class="col-12 mt-4">
						<button type="submit" name="add_dep_button" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
							Add Department
						</button>
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
	</body>

</html>