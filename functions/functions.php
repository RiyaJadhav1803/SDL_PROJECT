<?php

$con = mysqli_connect("localhost","root","","social_network") or die("Connection was not established");

//function for inserting post

function insertPost() {
    if (isset($_POST['sub'])) {
        global $con;
        global $user_id;

        $content = htmlentities($_POST['content']);
        $upload_image = $_FILES['upload_image']['name'];
        $image_tmp = $_FILES['upload_image']['tmp_name'];
        $random_number = rand(1, 100);

        if (strlen($content) > 250) {
            echo "<script>alert('Please use 250 or fewer characters!')</script>";
            echo "<script>window.open('home.php', '_self')</script>";
            exit();
        } else {
            // Case 1: Both image and content are present
			if (!empty($upload_image) && !empty($content)) {
				$image_path = "imagepost/" . $random_number . "_" . $upload_image;
			
				// Move uploaded file with error handling
				if (move_uploaded_file($image_tmp, $image_path)) {
					// Prepare the SQL query
					$insert = "INSERT INTO posts (id, post_content, post_image, post_date) 
							   VALUES ('$user_id', '$content', '$image_path', NOW())";
					$run = mysqli_query($con, $insert);
			
					if ($run) {
						echo "<script>alert('Your post has been updated successfully!')</script>";
						echo "<script>window.open('home.php', '_self')</script>";
			
						$update = "UPDATE users SET posts='yes' WHERE user_id='$user_id'";
						mysqli_query($con, $update);
					} else {
						die("Error inserting post: " . mysqli_error($con));
					}
				} else {
					echo "<script>alert('Failed to upload image. Please check folder permissions.')</script>";
				}
				exit();
			}
			
            // Case 2: Only image is present
			else if (!empty($upload_image) && empty($content)) {
				$image_path = "imagepost/" . $random_number . "_" . $upload_image;
			
				// Move uploaded file with error handling
				if (move_uploaded_file($image_tmp, $image_path)) {
					// Prepare the SQL query
					$insert = "INSERT INTO posts (id, post_content, post_image, post_date) 
							   VALUES ('$user_id', 'No content', '$image_path', NOW())";
					$run = mysqli_query($con, $insert);
			
					if ($run) {
						echo "<script>alert('Your image has been posted successfully!')</script>";
						echo "<script>window.open('home.php', '_self')</script>";
			
						$update = "UPDATE users SET posts='yes' WHERE id='$user_id'";
						mysqli_query($con, $update);
					} else {
						die("Error inserting image post: " . mysqli_error($con));
					}
				} else {
					echo "<script>alert('Failed to upload image. Please check folder permissions.')</script>";
				}
				exit();
			}
			
            // Case 3: Only content is present
            else if (!empty($content)) {
                $insert = "INSERT INTO posts (id, post_content, post_date) 
                           VALUES ('$user_id', '$content', NOW())";
                $run = mysqli_query($con, $insert);

                if ($run) {
                    echo "<script>alert('Your post has been updated successfully!')</script>";
                    echo "<script>window.open('home.php', '_self')</script>";

                    $update = "UPDATE users SET posts='yes' WHERE id='$user_id'";
                    mysqli_query($con, $update);
                }
                exit();
            }
            // Case 4: Neither image nor content is present
            else {
                echo "<script>alert('Error: No content or image to post!')</script>";
                echo "<script>window.open('home.php', '_self')</script>";
                exit();
            }
        }
    }
}


function get_posts(){
	global $con;
	$per_page = 4;

	if(isset($_GET['page'])){
		$page = $_GET['page'];
	}else{
		$page=1;
	}

	$start_from = ($page-1) * $per_page;

	$get_posts = "select * from posts ORDER by 1 DESC LIMIT $start_from, $per_page";

	$run_posts = mysqli_query($con, $get_posts);

	while($row_posts = mysqli_fetch_array($run_posts)){

		$post_id = $row_posts['post_id'];
		$user_id = $row_posts['id'];
		$content = substr($row_posts['post_content'], 0,40);
		$upload_image = $row_posts['post_image'];
		$post_date = $row_posts['post_date'];

		$user = "select *from users where id='$user_id' AND posts='yes'";
		$run_user = mysqli_query($con,$user);
		$row_user = mysqli_fetch_array($run_user);

		$user_name = $row_user['user_name'];
		$user_image = $row_user['user_image'];

		if($content=="No" && strlen($upload_image) >= 1){
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<img id='posts-img' src='$upload_image' style='height:350px;'>
						</div>
					</div><br>
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}

		else if(strlen($content) >= 1 && strlen($upload_image) >= 1){
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<p>$content</p>
							<img id='posts-img' src='$upload_image' style='height:350px;'>
						</div>
					</div><br>
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}

		else{
			echo"
			<div class='row'>
				<div class='col-sm-3'>
				</div>
				<div id='posts' class='col-sm-6'>
					<div class='row'>
						<div class='col-sm-2'>
						<p><img src='users/$user_image' class='img-circle' width='100px' height='100px'></p>
						</div>
						<div class='col-sm-6'>
							<h3><a style='text-decoration:none; cursor:pointer;color #3897f0;' href='user_profile.php?u_id=$user_id'>$user_name</a></h3>
							<h4><small style='color:black;'>Updated a post on <strong>$post_date</strong></small></h4>
						</div>
						<div class='col-sm-4'>
						</div>
					</div>
					<div class='row'>
						<div class='col-sm-12'>
							<h3><p>$content</p></h3>
						</div>
					</div><br>
					<a href='single.php?post_id=$post_id' style='float:right;'><button class='btn btn-info'>Comment</button></a><br>
				</div>
				<div class='col-sm-3'>
				</div>
			</div><br><br>
			";
		}
	}

	include("pagination.php");
}

?>
