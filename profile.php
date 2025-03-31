



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
		$get_user = "SELECT * FROM users WHERE user_email='$user'";
		$run_user = mysqli_query($con,$get_user);
		$row = mysqli_fetch_array($run_user);

		$user_name = $row['user_name'];
	?>
	<title><?php echo "$user_name"; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="style/home_style2.css">
	<style>
		body { background-color: #f4f4f4; }
		.navbar { background-color:rgb(255, 0, 111); }
		.navbar a { color: white; }
		.container { margin-top: 20px; }
		.profile-header { position: relative; text-align: center; }
		#cover-img { height: 400px; width: 100%; object-fit: cover; }
		#profile-img { width: 150px; height: 150px; border-radius: 50%; position: absolute; top: 250px; left: 50%; transform: translateX(-50%); border: 4px solid white; }
		.profile-actions { margin-top: 20px; } 
		.profile-info { background-color: white; padding: 20px; margin-top: 20px; border-radius: 8px; }
	</style>
</head>
<body>
<div class="container">
	<nav class="navbar navbar-expand-lg">
		<a class="navbar-brand" href="#">SocialApp</a>
		<div class="ml-auto">
			<img src="<?php echo $user_image; ?>" class="rounded-circle" width="40" height="40">
			<span class="text-white ml-2">Welcome, <?php echo $user_name; ?>!</span>
		</div>
	</nav>
	<div class="profile-header">
		<img id="cover-img" src="<?php echo $user_cover; ?>" alt="cover">
		<img id="profile-img" src="<?php echo $user_image; ?>" alt="profile">
	</div>
	<div class="text-center profile-actions">
		<form action='profile.php?u_id=<?php echo $user_id; ?>' method='post' enctype='multipart/form-data'>
			<input type='file' name='u_cover' class='btn btn-outline-primary'>
			<button name='submit' class='btn btn-primary'>Update Cover</button>
		</form>
		<form action='profile.php?u_id=<?php echo $user_id; ?>' method='post' enctype='multipart/form-data'>
			<input type='file' name='u_image' class='btn btn-outline-primary'>
			<button name='update' class='btn btn-primary'>Update Profile</button>
		</form>
	</div>
	<div class="profile-info">
		<h2><?php echo $first_name . " " . $last_name; ?></h2>
		<p><strong>Lives In:</strong> <?php echo $user_country; ?></p>
		<p><strong>Member Since:</strong> <?php echo $register_date; ?></p>
		<p><strong>Gender:</strong> <?php echo $user_gender; ?></p>
		<p><strong>Date of Birth:</strong> <?php echo $user_birthday; ?></p>
		<p><strong>About Me:</strong> <?php echo $describe_user; ?></p>
	</div>
</div>

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


</body>
</html>
