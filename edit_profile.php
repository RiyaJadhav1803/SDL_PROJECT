<?php
session_start();
include("includes/connection.php");
include("functions/functions.php");

if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('You must log in first!')</script>";
    echo "<script>window.open('signin.php', '_self')</script>";
    exit();
}

$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($con, $get_user);
$row = mysqli_fetch_array($run_user);

$user_name = $row['user_name'];
$first_name = $row['f_name'];
$last_name = $row['l_name'];
$describe_user = $row['describe_user'];
$relationship_status = $row['Relationship'];
$user_country = $row['user_country'];
$user_gender = $row['user_gender'];
$user_birthday = $row['user_birthday'];
$user_pass = $row['user_pass'];
$user_image = $row['user_image'];

if (isset($_POST['update'])) {
    $user_name = htmlentities($_POST['user_name']);
    $first_name = htmlentities($_POST['first_name']);
    $last_name = htmlentities($_POST['last_name']);
    $describe_user = htmlentities($_POST['describe_user']);
    $relationship_status = htmlentities($_POST['relationship_status']);
    $user_country = htmlentities($_POST['user_country']);
    $user_gender = htmlentities($_POST['user_gender']);
    $user_birthday = htmlentities($_POST['user_birthday']);
    $user_pass = htmlentities($_POST['user_pass']);

    // Profile picture update
    $u_image = $_FILES['u_image']['name'];
    $image_tmp = $_FILES['u_image']['tmp_name'];
    $random_number = rand(1, 100);
    $image_path = "imageuser/" . $random_number . "_" . $u_image;

    if (!empty($u_image)) {
        move_uploaded_file($image_tmp, $image_path);
        $update_image = ", user_image='$image_path'";
    } else {
        $update_image = "";
    }

    $update = "UPDATE users SET 
        user_name='$user_name', f_name='$first_name', l_name='$last_name',
        describe_user='$describe_user', Relationship='$relationship_status',
        user_country='$user_country', user_gender='$user_gender',
        user_birthday='$user_birthday', user_pass='$user_pass' $update_image
        WHERE user_email='$user'";
    $run_update = mysqli_query($con, $update);

    if ($run_update) {
        echo "<script>alert('Profile Updated Successfully!')</script>";
        echo "<script>window.open('profile.php', '_self')</script>";
    } else {
        echo "<script>alert('Error updating profile!')</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        form {
            width: 50%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            color: #555;
            margin: 10px 0 5px;
            display: block;
        }

        input[type="text"], input[type="date"], input[type="password"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            transition: 0.3s;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="password"]:focus, textarea:focus {
            border-color: #555;
            background-color: #fff;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Edit Profile</h2>
<form method="post" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="user_name" value="<?php echo $user_name; ?>" required>

    <label>First Name:</label>
    <input type="text" name="first_name" value="<?php echo $first_name; ?>" required>

    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?php echo $last_name; ?>" required>

    <label>Description:</label>
    <textarea name="describe_user"><?php echo $describe_user; ?></textarea>

    <label>Relationship Status:</label>
    <input type="text" name="relationship_status" value="<?php echo $relationship_status; ?>" required>

    <label>Country:</label>
    <input type="text" name="user_country" value="<?php echo $user_country; ?>" required>

    <label>Gender:</label>
    <input type="text" name="user_gender" value="<?php echo $user_gender; ?>" required>

    <label>Birthday:</label>
    <input type="date" name="user_birthday" value="<?php echo $user_birthday; ?>" required>

    <label>Password:</label>
    <input type="password" name="user_pass" value="<?php echo $user_pass; ?>" required>

    <label>Profile Picture:</label>
    <input type="file" name="u_image">

    <button type="submit" name="update">Update Profile</button>
</form>

</body>
</html>
