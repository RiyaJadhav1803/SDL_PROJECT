<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Find College Members</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2 class="text-center">Find College Members</h2>
    <form class="form-inline" method="POST" action="">
        <input type="text" class="form-control" name="search_user" placeholder="Enter Name or Email">
        <button type="submit" class="btn btn-primary" name="search_btn">Search</button>
    </form>
    <br>
    <div class="results">
        <?php
        include("includes/connection.php");

        if (isset($_POST['search_btn'])) {
            $search_query = htmlentities($_POST['search_user']);
            $get_users = "SELECT * FROM users WHERE user_name LIKE '%$search_query%' OR user_email LIKE '%$search_query%'";
            $run_users = mysqli_query($con, $get_users);
            
            if (mysqli_num_rows($run_users) > 0) {
                while ($row = mysqli_fetch_array($run_users)) {
                    $user_name = $row['user_name'];
                    $user_email = $row['user_email'];
                    $user_image = $row['user_image'];
                    
                    echo "<div class='well'>
                            <img src='$user_image' width='50' height='50' class='img-circle'>
                            <strong>$user_name</strong> ($user_email)
                          </div>";
                }
            } else {
                echo "<p class='text-danger'>No members found.</p>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
