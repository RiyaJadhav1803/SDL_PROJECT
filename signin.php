<!DOCTYPE html>
<?php
session_start();

include("includes/connection.php");

	if (isset($_POST['login'])) {

		$email = htmlentities(mysqli_real_escape_string($con, $_POST['email']));
		$pass = htmlentities(mysqli_real_escape_string($con, $_POST['pass']));

		$select_user = "select * from users where user_email='$email' AND user_pass='$pass' AND status='verified'";
		$query= mysqli_query($con, $select_user);
		$check_user = mysqli_num_rows($query);

		if($check_user == 1){
			$_SESSION['user_email'] = $email;

			echo "<script>window.open('home.php', '_self')</script>";
		}else{
			echo"<script>alert('Your Email or Password is incorrect')</script>";
		}
	}
?> 


<html>
<head>
    <title>Signin | Campus Connect</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #ffe6f2;
            font-family: Arial, sans-serif;
        }
        .main-content {
            width: 40%;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            color: #ff1493;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #ff1493;
            color: white;
            width: 100%;
            border-radius: 30px;
        }
        .btn-custom:hover {
            background-color: #e60073;
        }
        .link-text {
            color: #ff1493;
            text-decoration: none;
        }
        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h2 class="header">Campus Connect</h2>
            <h4 class="text-center">Login to your account</h4>
            <form action="" method="post">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="pass" class="form-control" placeholder="Password" required>
                </div>
                <div class="text-right">
                    <a href="forgot_password.php" class="link-text">Forgot Password?</a>
                </div>
                <br>
                <button type="submit" class="btn btn-custom btn-lg" name="login">Login</button>
            </form>
            <br>
            <div class="text-center">
                <p>Don't have an account? <a href="signup.php" class="link-text">Sign up here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
