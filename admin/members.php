<?php

	/*
	=================================================
	== Manage Members page
	== You Can Add | Edite | Delete members from here
	=================================================
	*/

	session_start();

	$pagetiltle = 'Members';

    if (isset($_SESSION['Username'])) {

        include 'init.php';

        // My page content

        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

        if ($do == 'manage') { // Mange page 

            $query = '';

            if (isset($_GET['page']) && $_GET['page'] == 'pending') {

                $query = ' AND RegStatues = 0';
            }

                $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
                $stmt->execute();
                //Bring data as Rows
                $rows = $stmt->fetchAll();
?>
            <h1 class="text-center">Manage Members</h1>
            <?php
            if (! empty($rows)) { ?>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>UserName</td>
                            <td>Email</td>
                            <td>Full Name</td>
                            <td>Regiesterd Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach ($rows as $row ) {
                                
                                echo "<tr>";
                                    echo "<td>" . $row['UserID'] . "</td>";
                                    echo "<td>";
                                            if (empty($row['avatar'])) {
                                                echo 'default img path';
                                            } else {
                                            echo "<img src='uploads\avatars\\" . $row['avatar'] . "' alt='' >";
                                            }
                                    echo "</td>";
                                    echo "<td>" . "<a href='members.php?do=edit&userid=" . $row['UserID'] ."'>" . $row['UserName'] . " </a>" . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>";
                                    echo "<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . $row['AddDate'] . "</td>";
                                    echo "<td>
                                        <a href='members.php?do=edit&userid=". $row['UserID'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=delete&userid=". $row['UserID'] ." ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a> ";
                                    if ($row['RegStatues'] == 0 ) {
                                        echo "<a href='members.php?do=activate&userid=". $row['UserID'] . " 'class='btn btn-info activate'><i class='fa fa-check'></i> Activate </a>";
                                    }
                                    echo "</td>";
                                echo "</tr>";
                            }
                        ?> 
                    </table>
                </div>
                <a href='members.php?do=add' class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>    
            </div>
            <?php } else {
                    echo '<div class="container">';
                        echo '<div class="nice-msg">There is No Records To Show</div>';
                        echo '<a href=\'members.php?do=add\' class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
                    echo '</div>';
                } ?>
            

        <?php } elseif ($do =='add') { ?>
            
            <h1 class="text-center">Add New Member</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Username</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="username" class="form-control" autocomplete="off" required="required" placeholder="Username" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='password' name="password" class="password form-control" autocomplete="new-password" required="required" placeholder="Password" />
                                <i class="show-pass fa fa-eye fa-1x"></i>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">E-mail</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='email' name="email" class="form-control" required="required" placeholder="Email"/>
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="full" class="form-control" required="required" placeholder="Full-Name" />
                            </div>
                        </div>
                        <!--Start profile image-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">User Avatar</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='file' name="avatar" class="form-control" required="required" />
                            </div>
                        </div>
                        <!-- End profile image-->
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type='submit' value="Add Member" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>

        <?php 
        } elseif ($do == 'insert') {

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Insert Member</h1>";
                    echo "<div class='container'>";

                    // Upload Variales of avatart image
                    
                    $avtar = $_FILES['avatar'];                    
                    
                    $avatarname = $_FILES['avatar']['name'];
                    $avatarsize = $_FILES['avatar']['size'];
                    $avatartmp  = $_FILES['avatar']['tmp_name'];
                    $avatartype = $_FILES['avatar']['type'];

                    // List allwoed extentions of profile images
                    $avatartAllowedExtention = array("jpeg" , "jpg" , "png" );

                    // Get Avatart extentions

                    $avatartExtention = strtolower(end(explode('.', $avatarname)));
                    


                    // Get Variables From The Form Which i will add to my DB
                    $user = $_POST['username'];
                    $pass = $_POST['password'];
                    $email = $_POST['email'];
                    $name = $_POST['full'];

                    $hashpassword = sha1($_POST['password']);

                    // Validate The Form
                    $forerrors = array();
                    if (strlen($user) < 4 ) {
                        $forerrors[] = "UserName can\'t be less than <strong>4 characters</strong>";
                    }
                    if (empty($user)) {
                        $forerrors[] = "UserName can\'t be empty";
                    }
                    if (empty($pass)) {
                        $forerrors[] = "Password can\'t be empty";
                    }
                    if (empty($name)) {
                        $forerrors[] = "Name can\'t be empty";
                    }
                    if (empty($email)) {
                        $forerrors[] = "Email can\'t be empty";
                    }
                    if (! empty($avatarname) && ! in_array($avatartExtention , $avatartAllowedExtention)) {
                        $forerrors[] = "Upload Valid Image For your Profile";
                    }
                    if ( empty($avatarname)) {
                        $forerrors[] = "Profile Image Is Required";
                    }
                    if ($avatarsize > 4194304) {
                        $forerrors[] = "Image Can't be larger than 4 MB";
                    }
                    // Loop for show errors 
                    foreach ($forerrors as $Error) {
                        
                        $themsg =  "<div class='alert alert-danger'>" . $Error . "</div>";
                        redirectHome($themsg , 'back');
                    }


                    
                    // Check If There is No Errors Procceed update information
                    if (empty($forerrors)) {

                        $avatar = rand(0 , 1000000) . '_' . $avatarname;

                        move_uploaded_file($avatartmp, "uploads\avatars\\" . $avatar);

                        // Check if the info is repeted or not 
                        $check = checkitems("UserName" , "users" , $user);
                        if ($check == 1) {

                            $themsg =  "<div class='alert alert-danger'>this user is exist</div>";
                            redirectHome($themsg , 'back');
                        } else {
                            // insert user info in database
                            $stmt = $con->prepare("INSERT INTO 
                                                users(UserName , password , Email , FullName , RegStatues, AddDate , avatar)
                                                VALUES(:zuser , :zpass , :zmail , :zname , 0 , now(), :zavatar)");
                            $stmt->execute(array(

                            'zuser'   => $user,
                            'zpass'   => $hashpassword,
                            'zmail'   => $email,
                            'zname'   => $name,
                            'zavatar' => $avatar
                            ));

                            // Echo Success Mes
                            echo '<div class="container">';
                                $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Inserted</div>';
                                redirectHome($themsg , 'back');
                            echo "</div>";
                        }
                    }

                } else {

                    echo '<div class="container">';
                        $themsg = '<div class="alert alert-danger">You can\'t Browse this page direct</div>';
                        redirectHome($themsg , 'back');
                    echo "</div>";
                }
            
            echo "</div>";

        } elseif ($do == 'edit') { // Edite Page
            
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // if codition to see if id is exist or not

            // Select this id data 
            $stmt = $con->prepare("SELECT * FROM users where Userid = ? LIMIT 1");

            // Excute the data mean do the process
            $stmt->execute(array($userid));

            // Featch (bring) the data
            $row = $stmt->fetch();

            // The row Count
            $count = $stmt->rowCount();

            // If there is id show the form to user
            if ($count > 0) { ?>

            	<h1 class="text-center">Edite Member</h1>
            	<div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST" >
                        <input type="hidden" name="userid" value="<?php echo $userid ?>" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Username</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="username" class="form-control" value="<?php echo $row['UserName'] ?>" autocomplete="off" required="required" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Password</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='hidden' name="oldpassword" value="<?php echo $row['password'] ?>" />
                                <input type='password' name="newpassword" class="form-control" autocomplete="new-password" placeholder="Write if you want to change only" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">E-mail</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='email' name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Full Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
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

                    echo "<h1 class='text-center'>Update Member</h1>";
                    echo "<div class='container'>";

                    // Get Variables From The Form
                    $id = $_POST['userid'];
                    $user = $_POST['username'];
                    $email = $_POST['email'];
                    $name = $_POST['full'];

                    // Password Trick
                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                    // Validate The Form

                    $forerrors = array();
                    if (strlen($user) < 4 ) {
                        $forerrors[] = "UserName can\'t be less than <strong>4 characters</strong>";
                    }
                    if (empty($user)) {

                        $forerrors[] = "UserName can\'t be empty";
                        
                    }
                    if (empty($name)) {

                        $forerrors[] = "Name can\'t be empty";
                    }
                    if (empty($email)) {

                        $forerrors[] = "Email can\'t be empty";
                    }
                    // Loop for show errors 
                    foreach ($forerrors as $Error) {
                        
                        echo "<div class='alert alert-danger'>" . $Error . "</div>";

                    }
                    
                    // Check If There is No Errors Procceed update information
                    if (empty($forerrors)) {

                        $stmt2 = $con->prepare("SELECT * FROM users WHERE UserName = ? AND UserID != ?");
                        $stmt2->execute(array($user , $id));

                        $count = $stmt2->rowCount();

                        if ($count == 1 ) {
                            echo "Sorry This User IS Exist";
                        } else {

                            // Upadate the data base with this info
                            $stmt = $con->prepare("UPDATE users SET UserName = ? , Email = ? , FullName = ? , password = ? WHERE userid = ?");
                            $stmt->execute(array($user , $email , $name , $pass , $id));

                            // Echo Success Mes
                            echo '<div class="container">';
                            $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Updated</div>';
                            redirectHome($themsg , 'back');
                            echo "</div>";
                        }
                    }

                } else {
                    echo '<div class="container">';
                    $themsg = "<div class='alert alert-danger'>You can't Browse this page direct</div>";
                    redirectHome($themsg);
                    echo "</div>";
                }
            
            echo "</div>";

        } elseif ($do == 'delete') { // Delete Page

            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // if condition to see if id is exist or not

                // Select this id data 
                // $stmt = $con->prepare("SELECT * FROM users where Userid = ? LIMIT 1");

                $check = checkitems('userid' , 'users' , $userid);
                
                // Excute the data mean do the process
                // $stmt->execute(array($userid));

                // The row Count
                // $count = $stmt->rowCount();

                // If there is id show the form to user
                
                if ($check > 0) { 

                    $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");
                    $stmt->bindParam(":zuser" , $userid);
                    $stmt->execute();

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Deleted</div>';
                    redirectHome($themsg , 'back');

            } else {

                $themsg = "<div class='alert alert-danger'>This Is Wrong ID</div>";
                redirectHome($themsg);
            }
            echo "</div>";

        } elseif($do == 'activate') {

            echo "<h1 class='text-center'>Activate Member</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0; // if condition to see if id is exist or not

                $check = checkitems('userid' , 'users' , $userid);
                
                if ($check > 0) { 

                    $stmt = $con->prepare("UPDATE users SET RegStatues = 1 WHERE UserID = ?");
                    $stmt->execute(array($userid));

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Activated</div>';
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