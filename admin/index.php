<?php 
    session_start();
    $nonavbar = ''; // to forbidden the navbar from appearing at login page
    $pagetiltle = 'Log in'; 
    // if session is saved will take user always to dasgbord without login form
    if (isset($_SESSION['Username'])) {
        header('Location: dashboard.php'); // will lead to this page without login
    } 
	include 'init.php';

    echo "<p>user name is ayad" . "<br>  pass word is 123123</p>";

    // Form login function
    // Check if user comong from post request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $Username = $_POST['user'];
        $password = $_POST['pass'];
        $hashedpass = sha1($password);

        // Check if the user exist at the database

        $stmt = $con->prepare("SELECT 
                                    UserID, Username, password 
                                from 
                                    users 
                                where 
                                    Username = ? 
                                AND 
                                    password = ? 
                                AND 
                                    GroupID = 1
                                LIMIT 1");
        $stmt->execute(array($Username , $hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if count > 0 this mean the member is exist in the database
        if ($count > 0) {

            $_SESSION['Username'] = $Username; // Set name for current seetion
            $_SESSION['ID']       = $row['UserID'];  // Resiter Session ID
            header('Location: dashboard.php'); // take user to dashbord page (home)
            exit();
        }
    }

 ?>
	

    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
        <h4 class="text-center">Admin Login</h4>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="new-password"/>
        <input class="btn btn-primary btn-block" type="submit" value="Login" />
    </form>

<?php include $temp . 'footer.php'; ?>