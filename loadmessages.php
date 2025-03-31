<?php
session_start();
include("includes/connection.php");

if (!isset($_SESSION['user_email'])) {
    exit();
}

$user = $_SESSION['user_email'];

$get_messages = "SELECT * FROM messages 
                 WHERE receiver_email='$user' OR sender_email='$user' 
                 ORDER BY sent_at DESC";
$run_messages = mysqli_query($con, $get_messages);

while ($row = mysqli_fetch_array($run_messages)) {
    $sender_email = $row['sender_email'];
    $message = $row['message'];
    $timestamp = $row['sent_at'];

    echo "<p><strong>$sender_email:</strong> $message <small>($timestamp)</small></p>";
}
?>
