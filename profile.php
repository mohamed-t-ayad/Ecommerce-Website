<?php 
	session_start();
    $pagetiltle = 'Profile';
	include 'init.php';
	if(isset($_SESSION['user'])) {

		$getuser = $con->prepare("SELECT * FROM users WHERE UserName= ?");
		$getuser->execute(array($sessionUser));
		$info = $getuser->fetch();
		$userid = $info['UserID'];

?>

	<h1 class="text-center">My Profile</h1>
	<div class="information block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">My Information</div>
				<div class="panel-body">
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-unlock-alt fa-fw"></i>
							<span>Login Name</span>: <?php echo $info['UserName'] ?>
						</li>
						<li>
							<i class="fa fa-envelope-o fa-fw"></i>
							<span>Email</span>: <?php echo $info['Email'] ?>
						</li>
						<li>
							<i class="fa fa-user fa-fw"></i>
							<span>Full Name</span>: <?php echo $info['FullName'] ?>
						</li>
						<li>
							<i class="fa fa-calender fa-fw"></i>
							<span>Register Date</span>: <?php echo $info['AddDate'] ?>
						</li>
						<li>
							<i class="fa fa-tags fa-fw"></i>
							<span>Fav Category</span>: <?php echo $userid ?>
						</li>
					</ul>
					<a href="#" class="btn btn-default">Edite Info </a>
				</div>
			</div>
		</div>
	</div>

	<div id="#my-ads" class="my-adds">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"> My Items</div>
					<div class="panel-body">
						<?php

							$myitems = getAllFromAll("*" , "items" ,"WHERE Member_id =$userid" , "", "Item_ID");

							if (! empty ($myitems)) {
								echo "<div class='row'>";
									foreach ($myitems as $item) {
									echo '<div class="col-md-3 col-sm-6">';
										echo '<div class="thumbnail item-box">';
											if ($item['Approve'] == 0 ) { echo '<span class="approve-stat">Not Approved</span>';}
											echo '<span class="price">$' . $item['Price'] . '</span>';
											echo '<img class="img-responsive center-block" src="img.jpg" alt="Add 1">';
											echo '<div class="caption">';
												echo '<h3><a href="items.php?itemid=' . $item['Item_ID']  . '">' . $item['Name'] . '</a></h3>';
												echo '<p>'. $item['Description'] . '</p>';
												echo '<p class="date">'. $item['Add_Date'] . '</p>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
									}
								echo '</div>';
							} else {
								echo "There is No Adds To Show , Creat <a href='newadd.php'>New Add</a>";
							}
						?>
					</div>
			</div>
		</div>
	</div>
	

	<div class="latest-comments block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading">latest Comments</div>
				<div class="panel-body">
					<?php
						$myComments = getAllFromAll("comment" , "Comments" ,"where user_id = $userid" , "" , "Cid");


						/*
                        $stmt = $con->prepare("SELECT comment FROM Comments WHERE user_id = ?");
                        $stmt->execute(array($userid));
                        $comments = $stmt->fetchAll();
						*/

                        if (! empty($myComments)) {
                        	foreach ($myComments as $comment) {
                        		echo '<p>' . $comment['comment']  . '</p>';
                        	}

                        } else {
                        	echo "There is No Comments To show";
                        }

					?>
				</div>
			</div>
		</div>
	</div>
	
<?php

	} else {

		header('Location: login.php');
		exit();
	}


    include $temp . 'footer.php'; 
?>