<?php
	
	session_start();
	 include 'init.php';

 ?>


	
<div class="container">
	<div class="row">
		<?php
			if (isset($_GET['name'])) {
				$tag = $_GET['name'];
				echo "<h1 class='text-center'>" . $tag . "</h1>";

			$tagitems = getAllFromAll("*", "items" , "where tags like '%$tag%'" , "AND Approve = 1" , "Item_ID" );
			foreach ($tagitems as $item) {
				echo '<div class="col-md-3 col-sm-6">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price">' . $item['Price'] . '</span>';
						echo '<img class="img-responsive center-block" src="img.jpg" alt="Add 1">';
						echo '<div class="caption">';
							echo '<h3><a href="items.php?itemid=' . $item['Item_ID']  . '">' . $item['Name'] . '</a></h3>';
							echo '<p>'. $item['Description'] . '</p>';
							echo '<p class="date">'. $item['Add_Date'] . '</p>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
			
		}
		?>
	</div>
</div>



<?php include $temp . 'footer.php'; ?>