<?php 

    ob_start();  // Output Beffering Start


    session_start();

    // if session is saved will take user always to dasgbord without login form
    if (isset($_SESSION['Username'])) {

    	$pagetiltle = 'Dashboard';
        include 'init.php';

        // Variables Which used at LAtest items function
        $numUsers = 5;  // Number of things which will be Shown at PAge
        $latestUsers  = getlatest("*" , "users" , "UserID" , $numUsers ); // Function that used to show latest users


        $numItems = 5;
        $latestItems = getlatest("*" , "Items" , "Item_ID" , $numItems); // LAtest items Array

        $numcomments = 5;



        /*Start Dashboard*/
?>
        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="state st-members">
                        <i class="fa fa-users"></i>
                            <div class="info">
                                Total Members
                                <span><a href="members.php"><?php echo countitems('UserID' , 'users') ?></a></span>
                            </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=manage&page=pending"><?php echo checkitems('RegStatues' , 'users' , 0 ) ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?php echo countitems('Item_ID' , 'items') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="state st-comments">
                        <i class="fa fa-comment"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?php echo countitems('Cid' , 'comments') ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container latest">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>  Latest <?php echo $numUsers ?> users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php 
                                        if (! empty($latestUsers)) {
                                            foreach ($latestUsers as $user) {

                                                echo "<li>" . $user['UserName'] . '<a href="members.php?do=edit&userid=' . $user['UserID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit '; // Close edit to test activated
                                                // If condition must be after Span button
                                                if ($user['RegStatues'] == 0 ) { // Test if the member is activated
                                                    echo "<a href='members.php?do=activate&userid=". $user['UserID'] . " 'class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Activate </a>";
                                                }
                                                echo  '</span></a></li>';
                                            }
                                        } else {
                                            echo "There's No Items To show";
                                         }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $numItems ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php 
                                        if (! empty($latestItems)) {
                                            foreach ($latestItems as $item) {

                                                echo "<li>" . $item['Name'] . '<a href="items.php?do=edit&itemid=' . $item['Item_ID'] . '"><span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit '; // Close edit to test activated
                                                // If condition must be after Span button
                                                if ($item['Approve'] == 0 ) { // Test if the member is activated
                                                    echo "<a href='items.php?do=approve&itemid=". $item['Item_ID'] . " 'class='btn btn-info pull-right activate'><i class='fa fa-check'></i> Approve </a>";
                                                }
                                                echo  '</span></a></li>';
                                            }
                                        } else {
                                            echo "There's No Recordes To show";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start latest comment part -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-comments-o"></i>  Latest <?php echo $numcomments ?> Comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-plus fa-lg"></i>
                            </span>
                            <div class="panel-body">
                                <?php
                                    $stmt = $con->prepare("SELECT 
                                                    comments.* , users.UserName AS User
                                                FROM 
                                                    Comments
                                                INNER JOIN
                                                    users
                                                ON
                                                    users.UserID = comments.user_id

                                                ORDER BY
                                                    Cid DESC
                                                LIMIT $numcomments");
                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();

                                    if (! empty($comments)) {
                                        foreach ($comments as $comment ) {
                                            echo "<div class='comment-box'>";
                                                echo '<span class="member-n"><a href="members.php?do=edit&userid=' . $comment['user_id'] . '">
                                                        ' . $comment['User'] . '</a></span>';
                                                echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                            echo '</div>';
                                        }
                                    } else {
                                            echo 'There\'s No Comments To Show';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!-- End latest comment part -->
        </div>


        <?php
        include $temp . 'footer.php';

    } else {

    	header('Location: index.php');
    }

    ob_end_flush();
?>