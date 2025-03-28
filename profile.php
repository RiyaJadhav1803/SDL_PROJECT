<!DOCTYPE html>
<?php
session_start();
include("includes/header.php");

if(!isset($_SESSION['user_email'])){
	header("location: index.php");
}
?>
<html>
<head>
	<?php
		$user = $_SESSION['user_email'];
		$get_user = "select * from users where user_email='$user'";
		$run_user = mysqli_query($con,$get_user);
		$row = mysqli_fetch_array($run_user);

		$user_name = $row['user_name'];
	?>
	<title><?php echo "$user_name"; ?></title>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<style>
	#cover-img{
		height: 400px;
		width: 100%;
	}#profile-img{
		position: absolute;
		top: 160px;
		left: 40px;
	}
	#update_profile{
		position: relative;
		top: -33px;
		cursor: pointer;
		left: 93px;
		border-radius: 4px;
		background-color: rgba(0,0,0,0.1);
		transform: translate(-50%, -50%);
	}
	#button_profile{
		position: absolute;
		top: 82%;
		left: 50%;
		cursor: pointer;
		transform: translate(-50%, -50%);
	}
</style>
<body>
<div class="row">
	<div class="col-sm-2">	
	</div>
	<div class="col-sm-8">
		<?php
			echo"
			<div>
				<div><img id='cover-img' class='img-rounded' src='$user_cover' alt='cover'></div>
				<form action='profile.php?u_id=$user_id' method='post' enctype='multipart/form-data'>

				<ul class='nav pull-left' style='position:absolute;top:10px;left:40px;'>
					<li class='dropdown'>
						<button class='dropdown-toggle btn btn-default' data-toggle='dropdown'>Change Cover</button>
						<div class='dropdown-menu'>
							<center>
							<p>Click <strong>Select Cover</strong> and then click the <br> <strong>Update Cover</strong></p>
							<label class='btn btn-info'> Select Cover
							<input type='file' name='u_cover' size='60' />
							</label><br><br>
							<button name='submit' class='btn btn-info'>Update Cover</button>
							</center>
						</div>
					</li>
				</ul>

				</form>
			</div>
			<div id='profile-img'>
				<img src='$user_image' alt='Profile' class='img-circle' width='180px' height='185px'>
				<form action='profile.php?u_id='$user_id' method='post' enctype='multipart/form-data'>

				<label id='update_profile'> Select Profile
				<input type='file' name='u_image' size='60' />
				</label><br><br>
				<button id='button_profile' name='update' class='btn btn-info'>Update Profile</button>
				</form>
			</div><br>
			";
		?>
<?php
$_SESSION['user_email'] = $user_email;
$_SESSION['id'] = $row['id'];

$user_id = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $u_cover = $_FILES['u_cover']['name'];
    $image_tmp = $_FILES['u_cover']['tmp_name'];
    $random_number = rand(1, 100);

    if (empty($u_cover)) {
        echo "<script>alert('Please Select Cover Image')</script>";
        echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        exit();
    } else {
        $image_path = "cover/" . $u_cover . "." . $random_number;

		if (move_uploaded_file($image_tmp, $image_path)) {
            // Update query with prepared statement
            $update = "UPDATE users SET user_cover=? WHERE id=?";
            $stmt = mysqli_prepare($con, $update);

            // Check if the statement preparation was successful
            if (!$stmt) {
                echo "<script>alert('SQL Statement Preparation Failed: " . mysqli_error($con) . "')</script>";
                exit();
            }

            mysqli_stmt_bind_param($stmt, "si", $image_path, $user_id);

            // Execute the statement and check for success
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Your Cover Updated')</script>";
                echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
            } else {
                echo "<script>alert('Data type mismatch in bind_param.')</script>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('SQL Statement Preparation Failed: " . mysqli_error($con) . "')</script>";
        }
    }
}
?>


	</div>



	<?php

$_SESSION['user_email'] = $user_email;
$_SESSION['id'] = $row['id']; // Set the user ID in the session
if (isset($_POST['update'])) {
    // Check if user ID is set in the session
    if (!isset($_SESSION['id']) || !is_numeric($_SESSION['id'])) {
        echo "<script>alert('User ID is not set or is invalid.')</script>";
        exit();
    }

    $user_id = $_SESSION['id']; // Get the user ID from the session
    $u_image = $_FILES['u_image']['name'];
    $image_tmp = $_FILES['u_image']['tmp_name'];
    $random_number = rand(1, 100);

    if (empty($u_image)) {
        echo "<script>alert('Please select a profile image by clicking on your profile image.')</script>";
        echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
        exit();
    } else {
        $image_path = "imageuser/" . $u_image . "." . $random_number;
        
        // Move uploaded file to the 'users' directory
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Update query with prepared statement
            $update = "UPDATE users SET user_image=? WHERE id=?";
            $stmt = mysqli_prepare($con, $update);

            // Check if the statement preparation was successful
            if (!$stmt) {
                echo "<script>alert('SQL Statement Preparation Failed: " . mysqli_error($con) . "')</script>";
                exit();
            }

            mysqli_stmt_bind_param($stmt, "si", $image_path, $user_id);

            // Execute the statement and check for success
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Your profile has been updated.')</script>";
                echo "<script>window.open('profile.php?u_id=$user_id', '_self')</script>";
            } else {
                echo "<script>alert('Error executing the update query: " . mysqli_stmt_error($stmt) . "')</script>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('Failed to upload image. Please check file permissions.')</script>";
        }
    }
}

?>
	<div class="col-sm-2">
	</div>
</div>
<div class="row">
	<div class="col-sm-2">
	</div>
	<div class="col-sm-2" style="background-color: #e6e6e6;text-align: center;left: 0.9%;border-radius: 5px;">
		<?php
		echo"
			<center><h2><strong>About</strong></h2></center>
			<center><h4><strong>$first_name $last_name</strong></h4></center>
			<p><strong><i style='color:grey;'>$describe_user</i></strong></p><br>
			<p><strong>Relationship Status: </strong> $Relationship_status</p><br>
			<p><strong>Lives In: </strong> $user_country</p><br>
			<p><strong>Member Since: </strong> $register_date</p><br>
			<p><strong>Gender: </strong> $user_gender</p><br>
			<p><strong>Date of Birth: </strong> $user_birthday</p><br>
		";
		?>
	</div>
</div>
</body>
</html>