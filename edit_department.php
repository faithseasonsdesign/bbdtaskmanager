<?php

	session_start();
	require("connection.php");
	$session_dep_name = $_SESSION['departmentName'];
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
	
	
	$old_dep_name = $_SESSION['departmentName'];
	//update button is pressed code start here 
	if(isset($_POST['update_dep_button'])){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$dep_new_name = protected_data($_POST['dep_name_edit_input']);
			$dep_status = protected_data($_POST['dep_status_input']);
			//check if department name input field is not empty
			if(empty($dep_new_name)){
				$general_error = " failed to update the department";
				$error_section_prop = "bg-danger d-flex justify-content-center align-items-center text-white col-12 col-sm-12 col-md-12 py-2 font-weight-bold";
				$error_caption_prop = "text-danger";
				$deparment_name_error = " you cannot leave the department name empty";
			}
			else{
				//all the input fields are filled with data
				//check if the department name does not exist in two places 
				$departmentTbl = "departments";
				$existInTwoFieldsDepSql = "SELECT departmentName FROM $departmentTbl WHERE departmentName = '$dep_new_name' AND departmentName != '$old_dep_name' ";
				$existInTwoFieldsDepResults = mysqli_query($connection,$existInTwoFieldsDepSql);
				if(mysqli_num_rows($existInTwoFieldsDepResults)>0){
					//means the department already exist in the table
					$general_error = " cant use $dep_new_name for a department name it is already used for another department";
					$error_section_prop = "bg-danger d-flex justify-content-center align-items-center text-white col-12 col-sm-12 col-md-12";
					$error_caption_prop = "text-danger";
				}
				else{
					//department does not exist we can update our department 
					$update_sql = "UPDATE $departmentTbl SET departmentName = '$dep_new_name' , departmentStatus = '$dep_status'  WHERE departmentName = '$old_dep_name'";
					$update_query = mysqli_query($connection,$update_sql);
					if($update_query){
						//successfuly updated department 
						header("refresh:0;url=http://localhost//taskm//add_department.php");
						$general_error = "you have successfuly updated the department redirecting you now ";
						$error_section_prop = "bg-success d-flex justify-content-center align-items-center text-white col-12 col-sm-12 col-md-12 py-2 font-weight-bold";
					}
					else{
						//failed to update department
						$general_error = "failed to update the department ";
						$error_section_prop = "bg-danger d-flex justify-content-center align-items-center text-white col-12 col-sm-12 col-md-12 py-2 font-weight-bold";
					}
				}

			}
			//check if department name input is not empty coding ended here
		}
	}
	//update button is pressed code ended here
	
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
						<h6>
							You are now editing the <?php echo($_SESSION['departmentName']) ?>
							Department
						</h6>
						<h2 class="add_dep_caption text-dark">
							Edit Department
						</h2>
					</div>
					<div class="col-12 ">
						<h5 class="text-dark">
							Department Name
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="dep_name_edit_input" placeholder="Name Of Department" value="<?php echo($session_dep_name) ?>"></input>
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
						<button type="submit" name="update_dep_button" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
							Update Department
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