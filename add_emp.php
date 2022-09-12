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
	$departmentTbl = "deparment";
	$taskTbl = "tasks";
	$employeeTbl = "employees";
	//database information ended here
	
	$general_error = "";
	$error_caption_prop = "";
	$error_section_prop = "";
	
	$emp_fullname = "" ; $emp_fullname_error = "";
	$emp_email = "" ; $emp_email_error = "";
	$emp_number = "" ; $emp_number_error = "";
	$emp_password = "" ; $emp_password_error = "";
	$emp_status = "" ; $emp_status_error = "";
	$emp_img = "" ; $emp_img_error = "";
	$emp_department = "" ; $emp_department_error = "";
	$emp_role = "" ; $emp_role_error = "";
	
	
	//add button press start 
	if(isset($_POST['add_emp_button'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$emp_fullname = protected_data($_POST['emp_fullname_input']);
			$emp_email = protected_data($_POST['emp_email_input']);
			$emp_number = protected_data($_POST['emp_number_input']);
			$emp_password = protected_data($_POST['emp_password_input']);
			$emp_status = protected_data($_POST['emp_status_input']);
			$emp_department = protected_data($_POST['emp_department_input']);
			$emp_role = protected_data($_POST['emp_role_input']);
			//check if the inputs are filled with data or not 
			if(empty($emp_fullname) || empty($emp_email) || empty($emp_number) || empty($emp_password) || empty($emp_role))
			{
				$general_error = " failed to add employee to list of employees ";
				$error_caption_prop = "text-danger font-weight-bold";
				$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
				//one of the inputs field is empty check which one is empty 
				//employee fullname
				if(empty($emp_fullname)){
					$emp_fullname_error = " please enter the employee fullname ";
				}
				//employee email
				if(empty($emp_email)){
					$emp_email_error = " please enter the employee email ";
				}
				//employee number 
				if(empty($emp_number)){
					$emp_number_error = " please enter the employee number ";
				}
				//employee password
				if(empty($emp_password)){
					$emp_password_error = " please enter the employee temporary password ";
				}
				//employee role
				if(empty($emp_role)){
					$emp_role_error = " please enter the employee role ";
				}
				//checking which input field is specifically empty ended 
			}
			else{
				//all the inputs field are filled with data
				//check if email and phone number dont exist already
				$exist_fields_sql = "SELECT * FROM $employeeTbl WHERE empEmail = '$emp_email'  || empNumber = '$emp_number' ;";
				$exist_fields_query = mysqli_query($connection,$exist_fields_sql);
				$exists_results = mysqli_num_rows($exist_fields_query);
				if($exists_results>0){
					//the email or phone number is taken so we cant add a new employee
					$general_error = " Failed to add employee to the employee list ";
					$error_caption_prop = "text-danger font-weight-bold";
					$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
					//check if email is already taken by another employee
					$email_exist_sql = "SELECT * FROM $employeeTbl WHERE empEmail = '$emp_email';";
					$email_exist_query = mysqli_query($connection,$email_exist_sql);
					$email_exist = mysqli_num_rows($email_exist_query);
					if($email_exist>0){
						$emp_email_error = " the email you are trying to use already is already taken by another employee ";
					}
					//check if phone number is already taken by another employee
					$number_exist_sql = "SELECT * FROM $employeeTbl WHERE empNumber = '$emp_number';";
					$number_exist_query = mysqli_query($connection,$number_exist_sql);
					$number_exist = mysqli_num_rows($number_exist_query);
					if($number_exist>0){
						$emp_number_error = " the phone number you are trying to use already is already taken by another employee ";
					}
					//checking if one of them is taken end
				}
				//checking if email or number are taken code ended here
				else{
					//the email or phone number are not taken we can add check if the employee image is valid then add employee
					
					//image info start
					$fileOne  = $_FILES['imageOne'];
					$fileOneName = $_FILES['imageOne']["name"];
					$fileOneTmpName = $_FILES['imageOne']["tmp_name"];
					$fileOneType = $_FILES['imageOne']["type"];
					
					$fileOneExt = explode(".",$fileOneName);
					$fileOneActualExt =strtolower(end($fileOneExt));
					
					$allowedExtensions = array("jpg","jpeg","png");
					
					//images info ended
					
					if(!empty($fileOneName)){
						//you choose an image
						
						if(in_array($fileOneActualExt,$allowedExtensions)){
							//echo("dope the file is allowed<br>");
							//image one
							$imageOneNewName = uniqid('',true)."." . $fileOneActualExt;
							$fileOneDestination = 'uploads/' ."$imageOneNewName";				
							//check if we uploaded image successfuly
							if(move_uploaded_file($fileOneTmpName,$fileOneDestination)){
								$general_error = "uploaded images successfuly";
								$error_section_prop = "bg-success d-flex justify-content-center align-items-center py-3 col-12";
								
								//upload to the database
								
								$imageOne = protected_data($fileOneDestination);
								
								$sql = "INSERT INTO $employeeTbl(empFullname,empEmail,empNumber,empPassword,empStatus,empImg,empDepartment,empRole) VALUES('$emp_fullname','$emp_password','$emp_number','$emp_password','$emp_status','$imageOne','$emp_department','$emp_role');";
								$query = mysqli_query($connection,$sql);
								//$results = mysqli_num_rows($query);
								if($query){
									header( "refresh:1;url=http://localhost//taskm//add_employee.php" );
									$general_error = " employee added to the list of employee redirecting you";
								}
								else{
									//failed to upload
									$general_error = " failed to add employee to the list";
								}
							}
							else{
								//failed to upload the image
								echo("failed to upload the image<br>");
							}
						}
						else{
							echo("only jpg jpeg and png files allowed <br>");
						}
						
					}
					else{
						$general_error = " please select an profile image for the employee profile ";
						$error_caption_prop = "text-danger font-weight-bold";
						$error_section_prop = "bg-danger text-white d-flex justify-content-center align-items-center py-3 col-12";
						//echo("please make sure you chose images for all the fields<br>");
					}			
					//uploading of images ended
				}
			}
			//checking if the inputs are filled code ended here
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
				<form enctype="multipart/form-data" class="add-dep-form d-flex row py-5 text-center" style="background-color:white" action="<?php echo($_SERVER['PHP_SELF'])?>" method="post">
					<!--section that display an error code start here-->
					<div class="col-12 <?php echo($error_section_prop) ?>">
						<p class="general_error">
							<?php echo($general_error) ?>
						</p>
					</div>
					<!--section that display an error code ended here-->
					<div class="col-12 py-4">
						<h2 class="add_emp_caption text-dark">
							Add Employee
						</h2>
					</div>
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Fullname
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="emp_fullname_input" placeholder="eg Faith Matlaba" value="<?php echo($emp_fullname) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_fullname_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Email
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="emp_email_input" placeholder="eg faith@bbd.com" value="<?php echo($emp_email) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_email_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Number
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="emp_number_input" placeholder="eg 074044045" value="<?php echo($emp_number) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_number_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Password
						</h5>
						<input type="password" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="emp_password_input"  value="<?php echo($emp_password) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_password_error) ?>
						</p>
					</div>
					
					<div class="col-12">
						<h5 class="text-dark">
							Employee Status
						</h5>
						<select name="emp_status_input" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4">
							<option value="active">active</option>
							<option value="inactive">inactive</option>
						</select>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark mt-3">
							Employee Image
						</h5>
						<input type="file" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="imageOne"  value=""></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_img_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="mt-2">Employee Department</h5>
						<select class="py-2 col-12 col-sm-12 col-md-4 text-center text-dark font-weight-bold" name="emp_department_input">
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

					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark mt-3">
							Employee Role
						</h5>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="emp_role_input" placeholder="eg Designer" value="<?php echo($emp_role) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_role_error) ?>
						</p>
					</div>
					
					<div class="col-12 mt-4">
						<button type="submit" name="add_emp_button" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
							Add Employee
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