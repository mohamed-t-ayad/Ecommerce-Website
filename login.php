<?php
    
    ob_start();
	session_start();
    $pagetiltle = 'Login'; 

    if (isset($_SESSION['user'])) {
        header('Location: index.php');
    } 

	include 'init.php';

	// Check if user comong from post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['login'])) {
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedpass = sha1($pass);

            // Check if the user exist at the database

            $stmt = $con->prepare("SELECT 
                                        UserID , Username, password 
                                    from 
                                        users 
                                    where 
                                        Username = ? 
                                    AND 
                                        password = ? 
                                    ");
            $stmt->execute(array($user , $hashedpass));
            $get = $stmt->fetch(); // fetch info to depend on user id on adding new add
            $count = $stmt->rowCount();

            // if count > 0 this mean the member is exist in the database
            if ($count > 0) {

                $_SESSION['user'] = $user; // Set name for current seetion
                $_SESSION['uid'] = $get['UserID']; // fetched id for new add page (register id with the session)
                
                header('Location: index.php'); // take user to dashbord page (home)
                exit();
            }
        } else {

            $FormErrors = array(); // Form Errors to contain all errors if exist

            $username  = $_POST['username'];
            $password  = $_POST['password'];
            $password2 = $_POST['password2'];
            $email     = $_POST['email'];



            // User name Filter
            if (isset($username)) {

                $filterduser = filter_var($username, FILTER_SANITIZE_STRING);

                if (strlen($filterduser) < 4 ) {
                    $FormErrors[] = 'UserName Must Be Larger Than 4 Characters';
                }
            }
            // Password Filter
            if (isset($password) && isset($password2)) {

                // This step must be before sha1(pass) becoz hashed pass won't consider the deild empty . empty hashed have a value
                if (empty($password)) {
                    $FormErrors[] = 'Password Can\'t Be Empty';
                }

                if (sha1($password) !== sha1($password2)) {

                    $FormErrors[] = 'Sorry Password Isn\'t Match';
                }
            }

            //Email Filter
            if (isset($email)) {

                $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

                if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true ) {
                    $FormErrors[] = 'This Email Is Not Valid';  
                }
            }

            // No errors then start add to Database
            if (empty($forerrors)) {

                // Check if the info is repeted or not 
                $check = checkitems("UserName" , "users" , $username);
                if ($check == 1) {

                    $FormErrors[] = 'Sorry This User is exist'; 
                    
                } else {
                    
                    // insert user info in database
                    $stmt = $con->prepare("INSERT INTO 
                                        users(UserName , password , Email , RegStatues, AddDate)
                                        VALUES(:zuser , :zpass , :zmail , 0 , now())");
                    $stmt->execute(array(

                    'zuser' => $username,
                    'zpass' => sha1($password),
                    'zmail' => $email
                    ));

                    // Echo Success Mes
                    $SuccessMsg = 'Registeration Completed Successfully ';
                    
                }
            }            
        }
    }   
?>
	<div class="container login-page">
		<h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">Signup</span></h1>
		<!-- Start Login Form-->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
			<input class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required="required">
			<input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" required="required">  
			<input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
		</form>
		<!-- End Login Form-->

		<!-- Start Signup Form-->
		<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input pattern=".{4,}" title="UserName must be 4 Char" class="form-control" type="text" name="username" autocomplete="off" placeholder="Username" required="required">
			<input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" minlength="4" required="required">
			<input class="form-control" type="password" name="password2" placeholder="Confirm Password" autocomplete="new-password" minlength="4" required="required">
			<input class="form-control" type="email" name="email" autocomplete="off" placeholder="Email" required="required">
			<input class="btn btn-success btn-block" type="submit" name="signup" value="Signup">
		</form>
		<!-- End Signup Form-->
	</div>

    <div class="the-errors text-center">
        <?php
            if (!empty($FormErrors)) {
                foreach ($FormErrors as $error) {
                    echo "<div class='msg error'>" . $error . '</div>';
                }
            }

            if (isset($SuccessMsg)) {
                echo '<div class="msg success">' . $SuccessMsg . '</div>';
            }
        ?>
    </div>
</div>


<?php
	include $temp . 'footer.php';

    ob_end_flush();
?> 