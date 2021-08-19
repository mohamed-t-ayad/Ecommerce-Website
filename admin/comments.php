<?php

	/*
	=================================================
	== Manage Comments  page
	== You Can Edite | Delete ?| approve  comments from here
	=================================================
	*/

	session_start();

	$pagetiltle = 'Comments';

    if (isset($_SESSION['Username'])) {

        include 'init.php';

        // My page content

        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

            if ($do == 'manage') {

                $stmt = $con->prepare("SELECT 
                                            comments.* , items.Name AS Item_Name , users.UserName AS User
                                        FROM 
                                            Comments
                                        INNER JOIN
                                            items
                                        ON
                                            items.Item_ID = comments.item_id
                                        INNER JOIN
                                            users
                                        ON
                                            users.UserID = comments.user_id
                                        ORDER BY
                                                    Cid DESC
                                        ");
                $stmt->execute();
                //Bring data as Rows
                $rows = $stmt->fetchAll();
?>
            <h1 class="text-center">Manage Comments</h1>
            <?php 
            if (! empty($rows)){
            ?>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach ($rows as $row ) {
                                
                                echo "<tr>";
                                    echo "<td>" . $row['Cid'] . "</td>";
                                    echo "<td>" . $row['comment'] . "</td>";
                                    echo "<td>" . $row['Item_Name'] . "</td>";
                                    echo "<td>" . $row['User'] . "</td>";
                                    echo "<td>" . $row['comment_date'] . "</td>";
                                    echo "<td>
                                        <a href='comments.php?do=edit&comid=". $row['Cid'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='comments.php?do=delete&comid=". $row['Cid'] ." ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a> ";
                                    if ($row['status'] == 0 ) {
                                        echo "<a href='comments.php?do=approve&comid=". $row['Cid'] . " 'class='btn btn-info activate'><i class='fa fa-check'></i> Approve </a>";
                                    }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?> 
                    </table>
                </div>
            </div>
            <?php } else {
                        echo '<div class="container">';
                            echo '<div class="nice-msg">There is No Comments To Show</div>';
                        echo '</div>';
                }
            ?>
            
        <?php
        } elseif ($do == 'edit') { // Edite Page

            $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; // if codition to see if id is exist or not

            // Select this id data 
            $stmt = $con->prepare("SELECT * FROM Comments WHERE Cid = ?");

            // Excute the data mean do the process
            $stmt->execute(array($comid));

            // Featch (bring) the data
            $row = $stmt->fetch();

            // The row Count
            $count = $stmt->rowCount();

            // If there is id show the form to user
            if ($count > 0) { ?>

                <h1 class="text-center">Edite Comment</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST" >
                        <input type="hidden" name="comid" value="<?php echo $comid ?>" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Comment</label>
                            <div class="col-sm-10 col-md-4">
                                <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type='submit' value="Save" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>
            <?php 
                } else {
                    echo "<div class='container'>";
                        $themsg = '<div class="alert alert-danger">There is No such ID</div>';
                        redirectHome($themsg);
                    echo "</div>";
                }

        } elseif ($do == 'update') {
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo "<h1 class='text-center'>Update Comment</h1>";
                    echo "<div class='container'>";

                    // Get Variables From The Form
                    $comid   = $_POST['comid'];
                    $comment = $_POST['comment'];

                    // Upadate the data base with this info

                    $stmt = $con->prepare("UPDATE Comments SET comment = ? WHERE Cid = ?");
                    $stmt->execute(array($comment , $comid));

                    // Echo Success Mes
                    echo '<div class="container">';
                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Updated</div>';
                    redirectHome($themsg , 'back');
                    echo "</div>";

                } else {
                    echo '<div class="container">';
                    $themsg = "<div class='alert alert-danger'>You can't Browse this page direct</div>";
                    redirectHome($themsg);
                    echo "</div>";
                }
            
            echo "</div>";

        } elseif ($do == 'delete') { // Delete Page

            echo "<h1 class='text-center'>Delete Comment</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; // if condition to see if id is exist or not

                // Select this id data 
                // $stmt = $con->prepare("SELECT * FROM users where Userid = ? LIMIT 1");

                $check = checkitems('Cid' , 'comments' , $comid);

                // If there is id show the form to user
                
                if ($check > 0) { 

                    $stmt = $con->prepare("DELETE FROM Comments WHERE Cid = :zid");
                    $stmt->bindParam(":zid" , $comid);
                    $stmt->execute();

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Deleted</div>';
                    redirectHome($themsg , 'back');

            } else {

                $themsg = "<div class='alert alert-danger'>This Is Wrong ID</div>";
                redirectHome($themsg);
            }
            echo "</div>";

        } elseif($do == 'approve') {

            echo "<h1 class='text-center'>Approve Comment</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0; // if condition to see if id is exist or not

                $check = checkitems('Cid' , 'Comments' , $comid);
                
                if ($check > 0) { 

                    $stmt = $con->prepare("UPDATE Comments SET status = 1 WHERE Cid = ?");
                    $stmt->execute(array($comid));

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Comment Approved</div>';
                    redirectHome($themsg , 'back');

            } else {

                $themsg = "<div class='alert alert-danger'>This Is Wrong ID</div>";
                redirectHome($themsg);
            }
            echo "</div>";
        
        }
		// Include footer 
        include $temp . 'footer.php';

    } else {

    	header('Location: index.php');
    }