<?php

	session_start();
	require("connection.php");
	
	$_SESSION['employee_fullname'] = "";
	$_SESSION['employee_email'] = "";
	$_SESSION['employeeUsername'] = "";//used to check the user privillage
	$_SESSION['linkCondition'] = ""; //
	$_SESSION['dashboardLinkCondition'] = "";
	$_SESSION['routeTaskCondition'] = "";
	//sanitize start
	function protected_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
	//sanitize ended
	
	
	//login button click code start
	$login_username = ""; $login_username_error = "";
	$login_password = ""; $login_password_error = "";
	$general_error = "";
	$error_caption_prop = "";
	$error_section_prop = "";
	
	//database information start here
	$employeeTbl = "employees";
	//database information ended here
	
	if(isset($_POST['login_button'])){
		if($_SERVER['REQUEST_METHOD']== "POST"){
			//populate the variables first
			$login_username = protected_data($_POST['username_input']);
			$login_password = protected_data($_POST['password_input']);
			//check if inputs are empty or not 
			if(empty($login_username) || empty($login_password)){
				//one of the input is empty check which one
				$general_error = " Failed to login one of the inputs is empty ";
				$error_section_prop = " bg-danger text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center ";
				if(empty($login_username)){
					$login_username_error = " please enter your username or email ";
					$error_caption_prop = " text-danger font-weight-bold ";
				}
				if(empty($login_password)){
					$login_password_error = " please enter your password  ";
					$error_caption_prop = " text-danger font-weight-bold ";
				}
			}
			else{
				//all inputs field are filled
				//check if the credentials are correct 
				$validationSql = "SELECT empEmail , empPassword FROM $employeeTbl WHERE empEmail = '$login_username' AND empPassword = '$login_password';";
				$validationQuery = mysqli_query($connection,$validationSql);
				$employeeExist = mysqli_num_rows($validationQuery);
				if($employeeExist>0){
					//employee exist access his privillage information and additional information
					$employee_privillage_sql = "SELECT * FROM $employeeTbl WHERE empEmail = '$login_username' ; ";
					$employee_privillage_results = mysqli_query($connection,$employee_privillage_sql);
					while($employee_results = mysqli_fetch_assoc($employee_privillage_results)){
						$employee_privillage = $employee_results['empRole'];
						$_SESSION['employee_name'] = $employee_results['empFullname'];
						$_SESSION['employee_email'] = $employee_results['empEmail'];
					}
					//check if the employee is a director if they are take them to their dashboard 
					if($employee_privillage == "Director"){
						//navigate to the direcot dashboard
						header("refresh:0;url=http://localhost//taskm//dashboard.php");
						$_SESSION['linkCondition'] = "d-block";
						$_SESSION['dashboardLinkCondition'] = "dashboard.php";
						$_SESSION['routeTaskCondition'] = 'add_task.php';
						$general_error = " Logged in successfuly redirection you in 2 seconds ";
						$error_section_prop = "bg-success text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center";
					}
					//check if the employee is just a Designer or Developer and take them to their dashboard
					else{
						//navigate to the employees dashboard
						header("refresh:0;url=http://localhost//taskm//employeeDashboard.php");
						$_SESSION['linkCondition'] = "d-none";
						$_SESSION['dashboardLinkCondition'] = "employeedashboard.php";
						$_SESSION['employeeUsername'] = protected_data($_POST['username_input']);
						$general_error = " Logged in successfuly redirection you in 2 seconds ";
						$error_section_prop = "bg-success text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center";
					}
				}
				else{
					//employee does not exist 
					$error_section_prop = "bg-danger text-white font-weight-bold d-block col-12 py-3 d-flex align-items-center justify-content-center";
					$general_error = " failed to log in please check if your credentials are correct ";
				}
			}
			//check if inputs empty ended 
		}
	}
	
	//login button click code ended
?>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="css/bootstrap.min.css" rel="stylesheet"/>
		<link href="css/style.css" rel="stylesheet"/>
		<link href="css/all.min.css" rel="stylesheet"/>
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
						<a href="login.php" class="text-white">
							Home
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
				<form class="login-form d-flex row py-5 text-center" style="background-color:white" action="<?php echo($_SERVER['PHP_SELF'])?>" method="post">
					<!--section that display an error code start here-->
					<div class="col-12 <?php echo($error_section_prop) ?>">
						<p class="general_error">
							<?php echo($general_error) ?>
						</p>
					</div>
					<!--section that display an error code ended here-->
					<div class="col-12 py-4">
						<h2 class="edit_task_caption text-dark">
							Login
						</h2>
					</div>
					<div class="col-12 ">
						<h4 class="text-dark">
							Username
						</h4>
						<input type="text" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="username_input" placeholder="Username/email" value="<?php echo($login_username) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($login_username_error) ?>
						</p>
					</div>
					<div class="col-12">
						<h4 class="text-dark">
							Password
						</h4>
						<input type="password" class="py-2 text-center font-weight-bold col-12 col-sm-12 col-md-4" name="password_input" value="<?php echo($login_password) ?>"></input>
						<p class="<?php echo($error_caption_prop) ?>">
							<?php echo($login_password_error) ?>
						</p>
					</div>
					<div class="col-12">
						<button type="submit" name="login_button" class="btn btn-sm bg-success text-white font-weight-bold col-12 col-sm-12 col-md-4 py-2">
							Login
						</button>
					</div>
				</form>
			</div>
		</div>
		
		<!--tablets and small devices navigation list start-->
		<div id="sm-nav-list-wrapper" class="bg-dark py-5 text-white">
			<ul id="sm-nav-list" class="text-white text-center">
				<li class="sm-nav-item"><a class="text-white" href="#skill-wrapper">My Skills</a></li>
				<li class="sm-nav-item"><a class="text-white" href="#project-wrapper">Projects</a></li>
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