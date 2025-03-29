<?php
session_start();
include("includes/header.php");
include("includes/db.php"); // Ensure database connection is included

if (!isset($_SESSION['user_email'])) {
    header("location: index.php");
    exit();
}

$user = $_SESSION['user_email'];
$get_user = "SELECT * FROM users WHERE user_email='$user'";
$run_user = mysqli_query($con, $get_user);
$row = mysqli_fetch_array($run_user);
$user_id = $row['id'];
$user_name = $row['user_name'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style/home_style2.css">
</head>
<body>
<div class="container">
    <h2>Messages</h2>
    <div id="chat-box" style="border:1px solid #ccc; height:300px; overflow-y:scroll; padding:10px;">
        <!-- Messages will be loaded here dynamically -->
    </div>
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
        url: 'load_messages.php',
        method: 'GET',
        success: function(response) {
            $('#chat-box').html(response);
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
        url: 'send_message.php',
        method: 'POST',
        data: {sender_id: sender_id, receiver_id: receiver_id, message: message},
        success: function(response) {
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