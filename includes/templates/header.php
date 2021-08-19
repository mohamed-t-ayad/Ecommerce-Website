<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php gettitle(); ?></title>
		<link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.structure.min.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css; ?>front.css" />
	</head>

	<body>

		<div class="upper-bar">
          <div class="container">
	          <?php
	          	if (isset($_SESSION['user'])) { ?>

	          		<div class="btn-group my-info pull-right">
	          			<div>
	          				<img class="img-thumbnail img-circle" src="img.jpg" alt="">
		          			<span class="btn dropdown-toogler" data-toggle="dropdown">
		          				<?php echo $sessionUser ?> <span class="caret"></span>
		          			</span>
	          				<ul class="dropdown-menu">
	          					<li><a href="profile.php">My Profile</a></li>
	          					<li><a href="newadd.php">New Item</a></li>
	          					<li><a href="profile.php#my-ads">My Items</a></li>
	          					<li><a href="logout.php">Logout</a></li>
	          				</ul>
	          			</div>
	          		</div>
	          	<?php
			    } else {
	          ?>
	          	<a href="login.php">
	          		<span class="pull-right">Login/SignUp</span>
	          	</a>
	          	<?php } ?>
          </div>
        </div>
		<nav class="navbar  navbar-inverse navbar-fixed-top" style="z-index: 0;">
		  <div class="container">
		    <!-- Brand and toggle get grouped for better mobile display -->
		        <div class="navbar-header">
		              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
		                <span class="sr-only">Toggle navigation</span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		                <span class="icon-bar"></span>
		              </button>
		              <a class="navbar-brand" href="index.php">HomePage</a>
		            </div>
		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="app-nav">

		      <ul class="nav navbar-nav navbar-right">
		          <?php

		          	$myCats = getAllFromAll("*" , "categories" ,"WHERE parent = 0" , "" , "ID" , "ASC");

		          	foreach ($myCats as $cat) {
				        echo '<li>
				        		<a href="categories.php?pageid=' . $cat['ID'] . '"> ' . $cat['Name'] . '</a>
				        	</li>';
				    }
		          ?>
		      </ul>
		    </div>
		  </div>
		</nav>


