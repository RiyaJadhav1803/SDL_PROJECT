<?php
session_start();
// include("includes/db.php");

include("includes/header.php");

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

$user = $_SESSION['user_email'];

// Fetch user details
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($con, $get_user);
$row = mysqli_fetch_array($run_user);
$user_id = $row['id'];
$user_name = $row['user_name'];



// Handle message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Fetch sender and receiver emails
    $sender_query = "SELECT user_email FROM users WHERE id='$user_id'";
    $receiver_query = "SELECT user_email FROM users WHERE id='$receiver_id'";

    $run_sender = mysqli_query($con, $sender_query);
    $run_receiver = mysqli_query($con, $receiver_query);

    $sender_row = mysqli_fetch_array($run_sender);
    $receiver_row = mysqli_fetch_array($run_receiver);

    $sender_email = $sender_row['user_email'];
    $receiver_email = $receiver_row['user_email'];

    // Insert message into the database
    $insert_message = "INSERT INTO messages (sender_email, receiver_email, message) 
                       VALUES ('$sender_email', '$receiver_email', '$message')";
    mysqli_query($con, $insert_message);
    echo "<script>loadMessages();</script>";
    exit();
}

// Handle message loading
// if (isset($_GET['load']) && $_GET['load'] == 1) {
//     $user = $_SESSION['user_email'];

//     $get_messages = "SELECT * FROM messages 
//                      WHERE receiver_email='$user' OR sender_email='$user' 
//                      ORDER BY sent_at DESC";
//     $run_messages = mysqli_query($con, $get_messages);

//     while ($row = mysqli_fetch_array($run_messages)) {
//         $sender_email = $row['sender_email'];
//         $message = $row['message'];
//         $timestamp = $row['sent_at'];

//         echo "<p><strong>$sender_email:</strong> $message <small>($timestamp)</small></p>";
//     }
//     exit();
// }
// ?>

<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>   
        body { background-color: #f4f4f4; }
        .navbar { background-color: rgb(255, 0, 111); }
        .navbar a { color: white; }
        .container { margin-top: 20px; }
        #chat-data { border: 2px solid #ccc; height: 300px; overflow-y: scroll; padding: 10px; }
        .btn-primary { margin-top: 5px; }
    </style>
</head>
<body>    
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">SocialApp</a>
        <div class="ml-auto">
            <img src="<?php echo $row['user_image']; ?>" class="rounded-circle" width="40" height="40">
            <span class="text-white ml-2">Welcome, <?php echo $user_name; ?>!</span>
        </div>
    </nav>

    <div class="container">
        <h2>Messages</h2>
        
        <div id="chat-data"></div>
        <br> 
        <form id="message-form">
            <input type="hidden" id="sender_id" value="<?php echo $user_id; ?>">
            <label>To:</label>
            <select id="receiver_id" class="form-control">
                <?php
                $get_users = "SELECT * FROM users WHERE id != '$user_id'";
                $run_users = mysqli_query($con, $get_users);
                while ($user_row = mysqli_fetch_array($run_users)) { 
                    echo "<option value='" . $user_row['id'] . "'>" . $user_row['user_name'] . "</option>";
                }
                ?>
            </select>
            <br>
            <textarea id="message" class="form-control" placeholder="Type a message..."></textarea>
            <br>
            <button type="button" class="btn btn-primary" onclick="sendMessage()">Send</button>
        </form>
    </div>

    <script>
        function loadMessages() {
            $.ajax({
                url: 'loadmessages.php',
                method: 'GET',
                success: function(response) {
                    $('#chat-data').html(response);
                }
            });
        }

        function sendMessage() {
            var sender_id = $('#sender_id').val();
            var receiver_id = $('#receiver_id').val();
            var message = $('#message').val();

            if (message.trim() === '') {
                alert("Please enter a message");
                return;
            }

            $.ajax({
                url: 'messages.php',
                method: 'POST',
                data: { sender_id: sender_id, receiver_id: receiver_id, message: message },
                success: function() {
                    $('#message').val('');
                    loadMessages();
                }
            });
        }

        $(document).ready(function() {
            loadMessages();
            setInterval(loadMessages, 3000); // Refresh messages every 3 seconds
        });
    </script>
</body>
</html>
