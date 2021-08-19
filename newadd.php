<?php 
	session_start();
    $pagetiltle = 'Create New Add';
	include 'init.php';
	if(isset($_SESSION['user'])) {

		/*
		$getuser = $con->prepare("SELECT * FROM users WHERE UserName= ?");
		$getuser->execute(array($sessionUser));
		$info = $getuser->fetch();
		*/

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$FormErrors = array();

			$name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$price 	  = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
			$country  = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
			$status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
			$category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
			$tags 	  = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);

			if (strlen($name) < 3 ) {
				$FormErrors[] = 'Item Name Must Be At Least 4 Charcaters';
			}
			if (strlen($desc) < 10 ) {
				$FormErrors[] = 'Item Description Must Be At Least 10 Charcaters';
			}
			if (strlen($country) < 3 ) {
				$FormErrors[] = 'Item Name Must Be At Least 3 Charcaters';
			}
			if (empty($price)) {
				$FormErrors[] = 'Price Is Required';
			}
			if (empty($status)) {
				$FormErrors[] = 'Status Is Required';
			}
			if (empty($category)) {
				$FormErrors[] = 'Category Is Required';
			}

			// Check If There is No Errors Procceed update information
	            if (empty($FormErrors)) {

	                    // insert user info in database
	                    $stmt = $con->prepare("INSERT INTO 
	                                        items(Name , Description , Price , Country_Made , Status, Add_Date , Cat_ID , Member_id , tags)
	                                        VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags )");
	                    $stmt->execute(array(

	                    'zname'     => $name,
	                    'zdesc'     => $desc,
	                    'zprice'    => $price,
	                    'zcountry'  => $country,
	                    'zstatus'   => $status,
	                    'zcat'      => $category,
	                    'zmember'   => $_SESSION['uid'], // registerd id to depend on 
	                    'ztags'		=> $tags
	                    ));

	                    // Echo Success Mes
	                    if ($stmt) {
	                    	$SuccessMsg = "Item Added";
	                    }
                        
                    }

		}

?>

	<h1 class="text-center"><?php echo $pagetiltle ?></h1>
	<div class="create-add block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"><?php echo $pagetiltle ?></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8">
							 <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			                    <!--Start Name field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Name</label>
			                        <div class="col-sm-10 col-md-9">
			                            <input type="text" name="name" class="form-control live-name"  placeholder="Name of the item" autocomplete="off" >
			                        </div>
			                    </div>
			                    <!--End Name field-->
			                    <!--Start Description field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Description</label>
			                        <div class="col-sm-10 col-md-9">
			                            <input type="text" name="description" class="form-control live-desc"  placeholder="Description of your item" autocomplete="off">
			                        </div>
			                    </div>
			                    <!--End Description field-->
			                    <!--Start Price field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Price</label>
			                        <div class="col-sm-10 col-md-9">
			                            <input type="text" name="price" class="form-control live-price"  placeholder="Price of your item" " >
			                        </div>
			                    </div>
			                    <!--End Price field-->
			                    <!--Start Country made field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Country</label>
			                        <div class="col-sm-10 col-md-9">
			                            <input type="text" name="country" class="form-control" placeholder="Country of made" >
			                        </div>
			                    </div>
			                    <!--End Country made field-->
			                    <!--Start Status field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Status</label>
			                        <div class="col-sm-10 col-md-9">
			                            <select name="status">
			                                <option value="0">..</option>
			                                <option value="1">New</option>
			                                <option value="2">Like New</option>
			                                <option value="3">Mid</option>
			                                <option value="4">Old</option>
			                            </select>
			                        </div>
			                    </div>
			                    <!--End Status field-->
			                    <!--Start Category field-->
			                    <div class="form-group form-group-lg">
			                        <label class="col-sm-3 control-label">Category</label>
			                        <div class="col-sm-10 col-md-9">
			                            <select name="category">
			                                <option value="0">..</option>
			                                <?php 

			                                	$cats = getAllFromAll('*' , 'categories', '' , '' , 'ID');
			                                   /* old steps before new function
			                                    $stmt2 = $con->prepare("SELECT * FROM categories");
			                                    $stmt2 -> execute();
			                                    $cats = $stmt2 ->fetchAll();
			                                    */
			                                    foreach ($cats  as $category) {
			                                        echo "<option value=' " . $category['ID'] . " '>" . $category['Name']  . "</option>";
			                                    } 
			                                ?>
			                            </select>
			                        </div>
			                    </div>
			                    <!--End Category field-->
			                    <!--Start Tags  field-->
	                            <div class="form-group form-group-lg">
	                                <label class="col-sm-3 control-label">Tags</label>
	                                <div class="col-sm-10 col-md-9">
	                                    <input type="text" name="tags" class="form-control" placeholder="Sebrate Tags With Comma (,)" value="<?php echo $item['tags']; ?>" >
	                                </div>
	                            </div>
	                        	<!--End Tags  field-->
			                    <!--Start Submit button-->
			                    <div class="form-group">
		                            <div class="col-sm-offset-3 col-sm-9">
		                                <input type='submit' value="Add Item" class="btn btn-primary btn-sm" />
		                            </div>
		                        </div>
			                        <!--End Submit button-->
			                </form>
						</div>
						<div class="col-md-4">
							<?php
								echo '<div class="thumbnail item-box live-preview">';
									echo '<span class="price">$0</span>';
									echo '<img class="img-responsive center-block" src="img.jpg" alt="Add 1">';
									echo '<div class="caption">';
										echo '<h3>Title</h3>';
										echo '<p>Des</p>';
									echo '</div>';
								echo '</div>';
							?>
						</div>
					</div>
					<!-- Start Looping Through Errors-->
					<?php 
						if (!empty($FormErrors)) {
							foreach ($FormErrors as $error) {
								echo '<div class="alert alert-danger">' . $error . '</div>';
							}
						}
						if (isset($SuccessMsg)) {
			                echo '<div class="alert alert-success">' . $SuccessMsg . '</div>';
			            }
					?>
					<!-- End Looping Through Errors-->
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