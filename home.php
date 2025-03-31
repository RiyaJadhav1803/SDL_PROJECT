<!DOCTYPE html>
<?php
session_start();

include("includes/header.php");
// include("includes/db.php"); // Ensure database connection is included

if (!isset($_SESSION['user_email'])) {
	echo "<script>window.open('signin.php', '_self')</script>";
    header("location: index.php");
    exit();
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
<html>
<head>
    <title><?php echo "$user_name"; ?> - Home</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style.css">
    <style>
        body { background-color: #f4f4f4; }
        .navbar { background-color:rgb(255, 0, 111); }
        .navbar a { color: white; }
        .container { margin-top: 20px; }
        .post-box { background: white; padding: 20px; border-radius: 10px; }
        .post-box textarea { resize: none; border-radius: 5px; }
        .feed .post { background: white; padding: 15px; margin-bottom: 15px; border-radius: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">SocialApp</a>
        <div class="ml-auto">
            <img src="<?php echo $user_image; ?>" class="rounded-circle" width="40" height="40">
            <span class="text-white ml-2">Welcome, <?php echo $user_name; ?>!</span>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="post-box">
                    <form action="home.php?id=<?php echo $user_id; ?>" method="post" enctype="multipart/form-data">
                        <textarea class="form-control" rows="4" name="content" placeholder="What's on your mind?"></textarea><br>
                        <input type="file" name="upload_image" class="form-control-file"><br>
                        <button class="btn btn-primary btn-block" name="sub">Post</button>
                    </form>
                    <?php insertPost(); ?>
                </div>
                <div class="feed">
                    <h3>News Feed</h3>
                    <?php echo get_posts(); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
