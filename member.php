<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
}

$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email=?";
$stmt = mysqli_prepare($con, $get_user);
mysqli_stmt_bind_param($stmt, "s", $user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

$user_name = $row['user_name'];
$user_id = $row['id'];
$user_image = $row['user_image'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Find College Members</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style.css">
</head>
<style>
        body { background-color: #f4f4f4; }
        .navbar { background-color:rgb(255, 0, 111); }
        .navbar a { color: white; }
        .container { margin-top: 20px; }
        /* .post-box { background: white; padding: 20px; border-radius: 10px; }
        .post-box textarea { resize: none; border-radius: 5px; }
        .feed .post { background: white; padding: 15px; margin-bottom: 15px; border-radius: 10px; } */
</style>

<body>
        <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="#">SocialApp</a>
            <div class="ml-auto">
                <img src="<?php echo $user_image; ?>" class="rounded-circle" width="40" height="40">
                <span class="text-white ml-2">Welcome, <?php echo $user_name; ?>!</span>
            </div>
        </nav>
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
