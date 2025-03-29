<!DOCTYPE html>
<html>
<head>
	<title>Campus Connect - Signup</title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<style>
	body{
		overflow-x: hidden;
		background-color: #ffe6f2;
	}
	.main-content{
		width: 50%;
		height: auto;
		margin: 50px auto;
		background-color: #fff;
		border-radius: 20px;
		box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
		padding: 40px 50px;
	}
	.header{
		text-align: center;
		color: #d63384;
		margin-bottom: 20px;
	}
	.well{
		background-color: #ff85a2;
		border-radius: 10px;
		color: white;
		text-align: center;
	}
	#signup{
		width: 100%;
		border-radius: 30px;
		background-color: #ff4d79;
		border: none;
		color: white;
		font-size: 18px;
		padding: 10px;
	}
	#signup:hover{
		background-color: #ff3366;
	}
</style>
<body>
<div class="row">
	<div class="col-sm-12">
		<div class="well">
			<h1>Campus Connect</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="main-content">
			<div class="header">
				<h3><strong>Join Campus Connect</strong></h3>
				<hr>
			</div>
			<div class="l-part">
				<form action="" method="post">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" placeholder="First Name" name="first_name" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" placeholder="Last Name" name="last_name" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" placeholder="Password" name="u_pass" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<input type="email" class="form-control" placeholder="Email" name="u_email" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						<input type="date" class="form-control" name="u_birthday" required>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<select class="form-control" name="u_gender" required>
							<option disabled selected>Select your Gender</option>
							<option>Male</option>
							<option>Female</option>
							<option>Others</option>
						</select>
					</div><br>
					<a style="text-decoration: none;float: right;color: #d63384;" href="signin.php">Already have an account?</a><br><br>

					<center><button id="signup" name="sign_up">Signup</button></center>
					<?php include("insert_user.php"); ?>
				</form>
			</div>
		</div>
	</div>
</div>
</body>
</html>