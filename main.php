<!DOCTYPE html>
<html>
<head>
    <title>SocialConnect - Login & Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #ffe6f2;
            text-align: center;
        }
        .welcome-section {
            padding: 50px 0;
        }
        .welcome-text h1 {
            font-size: 3rem;
            color: #ff4081;
        }
        .btn-custom {
            width: 60%;
            border-radius: 30px;
            padding: 10px;
            font-size: 1.2rem;
        }
        .btn-signup {
            background-color: #ff4081;
            color: white;
            border: none;
        }
        .btn-login {
            background-color: white;
            border: 2px solid #ff4081;
            color: #ff4081;
        }
        .btn-login:hover {
            background-color: #ff4081;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container welcome-section">
        <div class="row">
            <div class="col-md-6">
                <img src="images/socialconnect.jpeg" class="img-fluid" alt="Welcome to SocialConnect">
            </div>
            <div class="col-md-6 welcome-text">
                <h1>Join SocialConnect Today</h1>
                <p>Connect with your friends and the world around you.</p>
                <form method="post">
                    <button class="btn btn-custom btn-signup" name="signup">Sign Up</button><br><br>
                    <button class="btn btn-custom btn-login" name="login">Login</button>
                </form>
                <?php
                    if(isset($_POST['signup'])){
                        echo "<script>window.open('signup.php','_self')</script>";
                    }
                    if(isset($_POST['login'])){
                        echo "<script>window.open('signin.php','_self')</script>";
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
