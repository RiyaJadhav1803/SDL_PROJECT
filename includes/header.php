<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/connection.php");
include("functions/functions.php");

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    echo "<script>alert('You must log in first!')</script>";
    echo "<script>window.open('signin.php', '_self')</script>";
    exit();
}

$user = $_SESSION['user_email'];

// Use prepared statement to prevent SQL injection
$stmt = $con->prepare("SELECT * FROM users WHERE user_email = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo "<script>alert('User not found! Please log in again.')</script>";
    echo "<script>window.open('signin.php', '_self')</script>";
    exit();
}

$user_id = $row['id'];
$user_name = $row['user_name'];
$first_name = $row['f_name'];
$last_name = $row['l_name'];
$describe_user = $row['describe_user'];
$relationship_status = $row['Relationship'];
$user_email = $row['user_email'];
$user_country = $row['user_country'];
$user_gender = $row['user_gender'];
$user_birthday = $row['user_birthday'];
$user_image = $row['user_image'];
$user_cover = $row['user_cover'];
$recovery_account = $row['recovery_account'];
$register_date = $row['user_reg_date'];

// Secure the user_posts query
$stmt = $con->prepare("SELECT COUNT(*) AS post_count FROM posts WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_assoc()['post_count'];
$stmt->close();
?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php">Coding Cafe</a>
        </div>
  
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href='profile.php?<?php echo "u_id=$user_id"; ?>'><?php echo htmlspecialchars($first_name); ?></a></li>
                <li><a href="home.php">Home</a></li>
                <li><a href="member.php">Find People</a></li>
                <li><a href="messages.php?u_id=new">Messages</a></li>
                
                <li class='dropdown' >
                    <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>
                        <span><i class='glyphicon glyphicon-chevron-down'></i></span>
                    </a>
                    <ul class='dropdown-menu' >
                        <li>
                            <a style='color: black;' href='home.php'>My Posts <span class='badge badge-secondary'><?php echo $posts; ?></span></a>
                        </li>
                        <li>
                            <a style='color: black;' href='edit_profile.php?u_id=<?php echo $user_id; ?>'>Edit Account</a>
                        </li>
                        <li role='separator' class='divider'></li>
                        <li>
                            <a style='color: black;' href='logout.php'>Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <form class="navbar-form navbar-left" method="get" action="results.php">
                        <div class="form-group">
                            <input type="text" class="form-control" name="user_query" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-info" name="search">Search</button>
                    </form>
                </li> 
            </ul> -->
        </div>
    </div>
</nav>


