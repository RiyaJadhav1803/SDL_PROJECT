<!-- <?php
include("includes/connection.php"); // Ensure database connection is included

if (isset($_POST['sign_up'])) {
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['u_email']);
    $password = mysqli_real_escape_string($con, $_POST['u_pass']);
    $birthday = $_POST['u_birthday'];
    $gender = $_POST['u_gender'];

    // Hash password before saving
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Ensure email is not already registered
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $run_email = mysqli_query($con, $check_email);

    // Check if the query ran successfully
    if (!$run_email) {
        die("Database query failed: " . mysqli_error($con)); // Debugging line
    }

    if (mysqli_num_rows($run_email) > 0) {
        echo "<script>alert('Email already exists! Try another.')</script>";
    } else {
        // Insert user into database
        $insert_user = "INSERT INTO users (first_name, last_name, email, password, birthday, gender) 
                        VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$birthday', '$gender')";

        $run_user = mysqli_query($con, $insert_user);

        if ($run_user) {
            echo "<script>alert('Signup successful! You can now log in.')</script>";
            echo "<script>window.open('signin.php', '_self')</script>";
        } else {
            echo "<script>alert('Signup failed! Please try again.')</script>";
        }
    }
}
?> -->


<?php
include("includes/connection.php"); // Database connection file

if (isset($_POST['sign_up'])) {
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']); // Ensure this column exists in the DB
    $password = password_hash($_POST['u_pass'], PASSWORD_DEFAULT); // Secure password
    $birthday = $_POST['u_birthday'];
    $gender = $_POST['u_gender'];

    // ✅ Debugging: Print the column names in the users table
    $check_email = "SELECT * FROM users WHERE u_email='$email'";
    $run_email = mysqli_query($con, $check_email);

    if (!$run_email) {
        die("Query Failed: " . mysqli_error($con)); // Debugging
    }

    if (mysqli_num_rows($run_email) > 0) {
        echo "<script>alert('Email already registered!')</script>";
    } else {
        $insert_user = "INSERT INTO usersapp (first_name, last_name, u_email, password, birthday, gender) 
                        VALUES ('$first_name', '$last_name', '$email', '$password', '$birthday', '$gender')";

        $query = mysqli_query($con, $insert_user);

        if (!$query) {
            die("Insertion Failed: " . mysqli_error($con)); // ✅ Print error if insertion fails
        }

        echo "<script>alert('Registration Successful!'); window.location.href = 'signin.php';</script>";
    }
}
?>
