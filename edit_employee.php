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
	
	//employee old details from session start 
	$old_emp_number = $_SESSION['edit_employee_number']  ;
	$old_emp_email = $_SESSION['edit_employee_reference'];
	//employee old details from session ended
	
	//use session variables to autofill employee form start 
	$auto_fill_sql = "SELECT * FROM $employeeTbl WHERE empEmail = '$old_emp_email' ;";
	$auto_fill_query = mysqli_query($connection,$auto_fill_sql);
	$auto_fill_results = mysqli_num_rows($auto_fill_query);
	if($auto_fill_results > 0){
		while($employee_autofill = mysqli_fetch_assoc($auto_fill_query)){
			$autofill_fullname = $employee_autofill['empFullname'];
			$autofill_email = $employee_autofill['empEmail'];
			$autofill_number = $employee_autofill['empNumber'];
			$autofill_password = $employee_autofill['empPassword'];
			$autofill_role = $employee_autofill['empRole'];
		}
	}
	//use session variables to autofill employee form ended 
	
	
	//add button press start 
	if(isset($_POST['update_emp_button'])){
		if($_SERVER['REQUEST_METHOD']=="POST"){
			$emp_fullname = protected_data($_POST['update_emp_fullname_input']);
			$emp_email = protected_data($_POST['update_emp_email_input']);
			$emp_number = protected_data($_POST['update_emp_number_input']);
			$emp_password = protected_data($_POST['update_emp_password_input']);
			$emp_status = protected_data($_POST['update_emp_status_input']);
			$emp_department = protected_data($_POST['update_emp_department_input']);
			$emp_role = protected_data($_POST['update_emp_role_input']);
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
				//check if email and phone number exist in another field that is not the current field
				//check if the number  does not exist elsewhere except the current field
				$existInTwoFieldsEmpNumSql = "SELECT empNumber FROM $employeeTbl WHERE empNumber = '$emp_number' AND empNumber != '$old_emp_number' ";
				$existInTwoFieldsEmpNumResults = mysqli_query($connection,$existInTwoFieldsEmpNumSql);
				$existInTwoFieldsEmpNum = mysqli_num_rows($existInTwoFieldsEmpNumResults);
				//check if the email does not exist elsewhere except the current field
				$existInTwoFieldsEmpEmailSql = "SELECT empEmail departmentName FROM $employeeTbl WHERE empEmail = '$emp_email' AND empEmail != '$old_emp_email' ";
				$existInTwoFieldsEmpEmailResults = mysqli_query($connection,$existInTwoFieldsEmpEmailSql);
				$existInTwoFieldsEmpEmail = mysqli_num_rows($existInTwoFieldsEmpEmailResults);
				
				if($existInTwoFieldsEmpNum || $existInTwoFieldsEmpEmail ){
					//the current email is taken by another user but its okay if taken by the current one
					//check if number is taken by another user
					$general_error = "failed to update employee details";
					$error_section_prop = "bg-danger";
					if($existInTwoFieldsEmpNum){
						$emp_number_error = "the number is already taken by another employee";
						$error_caption_prop = "text-danger";
					}
					if($existInTwoFieldsEmpEmail){
						$emp_email_error = "the email is already taken by another employee";
						$error_caption_prop = "text-danger";
					}
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
								//now here sql have to update not add new member :)
								$sql = "UPDATE $employeeTbl SET empFullname ='$emp_fullname' ,empEmail = '$emp_email', empNumber ='$emp_number', empPassword='$emp_password' , empStatus = '$emp_status' , empImg = '$imageOne', empDepartment ='$emp_department' , empRole = '$emp_role' WHERE empEmail = '$old_emp_email';";
								$query = mysqli_query($connection,$sql);
								//$results = mysqli_num_rows($query);
								if($query){
									header( "refresh:1;url=http://localhost//taskm//add_employee.php" );
									$general_error = " employee update to the list of employee redirecting you";
								}
								else{
									//failed to upload
									$general_error = " failed to update employee to the list";
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
							Edit Employee Details
						</h2>
					</div>
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Fullname
						</h5>
						<input id="emp_fullname" type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="update_emp_fullname_input" placeholder="eg Faith Matlaba" value="<?php echo($autofill_fullname) ?>"></input>
						<p id="emp_fullname_error" class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_fullname_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Email
						</h5>
						<input id="emp_email" type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="update_emp_email_input" placeholder="eg faith@bbd.com" value="<?php echo($autofill_email) ?>"></input>
						<p id="emp_email_error" class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_email_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Number
						</h5>
						<input id="emp_number" type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="update_emp_number_input" placeholder="eg 074044045" value="<?php echo($autofill_number) ?>"></input>
						<p id="emp_number_error" class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_number_error) ?>
						</p>
					</div>
					
					<div class="col-12 col-sm-12 col-md-12">
						<h5 class="text-dark">
							Employee Password
						</h5>
						<input id="emp_password" type="password" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="update_emp_password_input"  value="<?php echo($autofill_password) ?>"></input>
						<p id="emp_password_error" class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_password_error) ?>
						</p>
					</div>
					
					<div class="col-12">
						<h5 class="text-dark">
							Employee Status
						</h5>
						<select name="update_emp_status_input" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4">
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
						<select class="py-2 col-12 col-sm-12 col-md-4 text-center font-weight-bold" name="update_emp_department_input">
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
						<input id="emp_role" type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="update_emp_role_input" placeholder="eg Designer" value="<?php echo($autofill_role) ?>"></input>
						<p id="emp_role_error" class="<?php echo($error_caption_prop) ?>">
							<?php echo($emp_role_error) ?>
						</p>
					</div>
					
					<div class="col-12 mt-4">
						<button type="submit" name="update_emp_button" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
							Update Employee
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
		
		<script>
			function formValidation(){
				
				var emp_fullname = document.getElementById('emp_fullname');
				var emp_fullname_error = document.getElementById('emp_fullname_error');
				
				var emp_email = document.getElementById('emp_email');
				var emp_email_error = document.getElementById('emp_email_error');
				
				var emp_number = document.getElementById('emp_number');
				var emp_number_error = document.getElementById('emp_number_error');
				
				var emp_password = document.getElementById('emp_password');
				var emp_password_error = document.getElementById('emp_password_error');
				
				var emp_role = document.getElementById('emp_role');
				var emp_role_error = document.getElementById('emp_role_error');
				
				//now validate 
				//fullname input
				emp_fullname.onmouseout = function(){
					if(emp_fullname.value==""){
						emp_fullname_error.innerHTML = " you cannot leave the fullname input field empty";
						emp_fullname_error.style.color = "red";
					}
					else{
						emp_fullname_error.innerHTML = "";
					}
				}
				//email input 
				emp_email.onmouseout = function(){
					if(emp_email.value==""){
						emp_email_error.innerHTML = " you cannot leave the email input field empty";
						emp_email_error.style.color = "red";
					}
					else{
						emp_email_error.innerHTML  = "";
					}
				}
				//number input f
				emp_number.onmouseout = function(){
					if(emp_number.value==""){
						emp_number_error.innerHTML = " you cannot leave the number input field empty";
						emp_number_error.style.color = "red";
					}
					else{
						emp_number_error.innerHTML = "";
					}
				}
				//password 
				emp_password.onmouseout = function(){
					if(emp_password.value==""){
						emp_password_error.innerHTML = " you cannot leave the password input field empty";
						emp_password_error.style.color = "red";
					}
					else{
						emp_password_error.innerHTML = "";
					}
				}
				//role 
				emp_role.onmouseout = function(){
					if(emp_role.value==""){
						emp_role_error.innerHTML = " you cannot leave the employe role input field empty";
						emp_role_error.style.color = "red";
					}
					else{
						emp_role_error.innerHTML = "";
					}
				}
				
			}
			formValidation();
		</script>
		
		<script src="js/jquery-3.4.1.min.js"></script>
		<script src="js/style.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>

</html>