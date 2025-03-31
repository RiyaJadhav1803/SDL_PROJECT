<?php
session_start();
include("includes/connection.php");
include("functions/functions.php");

if (!isset($_SESSION['user_email'])) {
    header("location: signin.php");
    exit();
}

$user = $_SESSION['user_email'];
$stmt = $con->prepare("SELECT * FROM users WHERE user_email = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

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

    $update_query = "UPDATE users SET user_name=?, f_name=?, l_name=?, describe_user=?, Relationship=?, user_country=?, user_gender=?, user_birthday=?, user_pass=? $update_image WHERE user_email=?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("ssssssssss", $user_name, $first_name, $last_name, $describe_user, $relationship_status, $user_country, $user_gender, $user_birthday, $user_pass, $user);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Profile Updated Successfully!</div>";
        echo "<script>window.open('profile.php', '_self')</script>";
    } else {
        echo "<div class='alert alert-danger'>Error updating profile!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        form { width: 50%; margin: 40px auto; padding: 20px; background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; }
        label { font-weight: bold; color: #555; margin: 10px 0 5px; display: block; }
        input, select, textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border-radius: 5px; border: 1px solid #ccc; background-color: #f9f9f9; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
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
    <select name="relationship_status">
        <option value="Single">Single</option>
        <option value="In a Relationship">In a Relationship</option>
        <option value="Married">Married</option>
    </select>

    <label>Country:</label>
    <input type="text" name="user_country" value="<?php echo $user_country; ?>" required>

    <label>Gender:</label>
    <select name="user_gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select>

    <label>Birthday:</label>
    <input type="date" name="user_birthday" value="<?php echo $user_birthday; ?>" required>

    <label>Password:</label>
    <input type="password" name="user_pass" >

    <label>Profile Picture:</label>
    <input type="file" name="u_image" onchange="previewImage(event)">
    <img id="imagePreview" src="<?php echo $user_image; ?>" width="100" height="100" style="display: block; margin-top: 10px;">

    <label>Bio/Skills:</label>
    <textarea name="user_bio"></textarea>

    <button type="submit" name="update">Update Profile</button>
</form>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>