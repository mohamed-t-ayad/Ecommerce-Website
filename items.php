<?php 
	session_start();
    $pagetiltle = 'Show Items';
	include 'init.php';

	$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; // if codition to see if id is exist or not

	// Select this id data 
	$stmt = $con->prepare("SELECT
								items.*,
                                    categories.Name AS Category_Name,
                                    users.UserName
                                 FROM 
                                    items
                                INNER JOIN 
                                    categories
                                 ON
                                     categories.ID = items.Cat_ID

                                INNER JOIN 
                                    users 
                                 ON
                                 users.UserID = items.Member_id
								 WHERE
								    Item_ID = ?
								AND
									Approve = 1");

	// Excute the data mean do the process
	$stmt->execute(array($itemid));

	$count = $stmt->rowCOunt();

	if ($count > 0 ) {

		// Featch (bring) the data
		$item = $stmt->fetch();
		?>
		<h1 class="text-center text-capitalize"><?php echo $item['Name']; ?></h1>
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<img class="img-responsive center-block img-thumbnail" src="img.jpg" alt="Add 1">
				</div>
				<div class="col-md-9 item-info">
					<h2> <?php echo $item['Name']; ?></h2>
					<p><?php echo $item['Description'] ?></p>
					<ul class="list-unstyled">
						<li>
							<i class="fa fa-calendar fa-fw"></i>
							<span>Added Date </span>:<?php echo $item['Add_Date'] ?>
						</li>
						<li>
							<i class="fa fa-money fa-fw"></i>
							<span>Price </span>:$<?php echo $item['Price'] ?>
						</li>
						<li>
							<i class="fa fa-building fa-fw"></i>
							<span> Made In </span>:<?php echo $item['Country_Made'] ?>
						</li>
						<li>
							<i class="fa fa-tags fa-fw"></i>
							<span>Category </span>:<a href="categories.php?pageid=<?php echo $item['Cat_ID']?>"><?php echo $item['Category_Name'] ?></a>
						</li>
						<li>
							<i class="fa fa-user fa-fw"></i>
							<span>Added By </span>:<a href='#'><?php echo $item['UserName'] ?></a>
						</li>
						<li class="item-tags">
							<i class="fa fa-user fa-fw"></i>
							<span>Tags</span>:
							<?php 
								$alltags = explode(" , ", $item['tags']);
								foreach ($alltags as $tag) {
									$tag = str_replace(" ", "", $tag);
									if(! empty($tag)) {
										echo  "<a href='tags.php?name={$tag}'>" . $tag . '</a> | ';
									}
								}
							?>
						</li>
					</ul>
				</div>
			</div>
			<hr class="custom-hr">
			<!-- Start Add Comment -->
			<?php if(isset($_SESSION['user'])) { ?>
			<div class="row">
				<div class="col-md-offset-3">
					<div class="add-comment">
						<h3>Add Your Comment </h3>
						<form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item["Item_ID"] ?>" method="POST">
							<textarea class="form-control" name="comment" required="required"></textarea>
							<input class="btn btn-primary" type="submit" value="Add Comment">
						</form>
						<?php
							if ($_SERVER['REQUEST_METHOD'] == 'POST') {
								$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
								$userid  = $_SESSION['udm_free_ispell_data(agent)'];
								$itemid  = $item['Item_ID'];

								if (! empty($comment)) {
									$stmt = $con->prepare("INSERT INTO
															comments(comment , status , comment_date , item_id , user_id)
															VALUES(:zcomment , 0 , NOW(), :zitemid , :zuserid)");
									$stmt->execute(array(

											'zcomment' => $comment,									
											'zuserid'  => $_SESSION['uid'],
											'zitemid'  => $itemid
										));

									if ($stmt) {
										echo '<div class="alert alert-success">Comment added</div>';
									}
								}
							}
						?>
					</div>
				</div>
			</div>
			<?php } else {
						echo '<a hreg="login.php">Login</a> Or <a hreg="login.php">Register</a> To Add Comment';
				} ?>
			<!-- End Add Comment -->
			<hr class="custom-hr">
			<?php
	                $stmt = $con->prepare("SELECT 
	                                            comments.* , users.UserName AS User
	                                        FROM 
	                                            Comments
	                                        INNER JOIN
	                                            users
	                                        ON
	                                            users.UserID = comments.user_id
	                                        WHERE 
	                                        	item_id = ?
	                                        AND 
	                                        	status = 1
	                                        ORDER BY
	                                            Cid DESC
	                                        ");
	                $stmt->execute(array($item['Item_ID']));
	                //Bring data as Rows
	                $comments = $stmt->fetchAll();
                ?>
			<?php
				foreach ($comments as $comment) { ?>
					<div class="comment-box">
						<div class="row">
							<div class="col-sm-2 text-center">
								<img class="img-responsive center-block img-thumbnail img-circle" src="img.jpg" alt="Add 1">
								<?php echo $comment['User']  ?>		
							</div>
		                	<div class="col-sm-10">
		                		<p class="lead"><?php echo $comment['comment'] ?> </p>	
		                	</div>
			        	</div>
					</div>
					<hr class="custom-hr">
	            <?php } ?>
		</div>
		<?php
	} else {
		echo '<div class="container">';	
			echo '<div class="alert alert-danger">There is No Such ID</div>';
		echo '</div>';
	}

    include $temp . 'footer.php'; 
?>