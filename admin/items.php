<?php


	/*
	====================================

	== Items  Page

	====================================
	*/

	ob_start(); // Output Beffering Start

	session_start();

	$pagetiltle = 'Items';

    if (isset($_SESSION['Username'])) {

        include 'init.php';


        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

        if ($do == 'manage') {


                $stmt = $con->prepare("select 
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
                                        ORDER BY
                                                    Item_ID DESC
                                        ");
                $stmt->execute();
                //Bring data as Rows
                $items = $stmt->fetchAll();
?>
            <h1 class="text-center">Manage Items</h1>

            <?php 
            if (! empty($items)){?>
                <div class="container">
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>#ID</td>
                                <td>Name</td>
                                <td>Description</td>
                                <td>Price</td>
                                <td>Adding Date</td>
                                <td>Category</td>
                                <td>UserName</td>
                                <td>Control</td>
                            </tr>
                            <?php
                                foreach ($items as $item ) {
                                    
                                    echo "<tr>";
                                        echo "<td>" . $item['Item_ID'] . "</td>";
                                        echo "<td>" . $item['Name'] . "</td>";
                                        echo "<td>" . $item['Description'] . "</td>";
                                        echo "<td>" . $item['Price'] . "</td>";
                                        echo "<td>" . $item['Add_Date'] . "</td>";
                                        echo "<td>" . $item['Category_Name'] . "</td>";
                                        echo "<td>" . $item['UserName'] . "</td>";
                                        echo "<td>
                                            <a href='items.php?do=edit&itemid=". $item['Item_ID'] ." ' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                            <a href='items.php?do=delete&itemid=". $item['Item_ID'] ." ' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a> ";
                                            if ($item['Approve'] == 0 ) {
                                                echo "<a href='items.php?do=approve&itemid=". $item['Item_ID'] . " 'class='btn btn-info activate'><i class='fa fa-check'></i> Approve </a>";
                                        }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                            ?> 
                        </table>
                    </div>
                    <a href='items.php?do=add' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>    
                </div>
                <?php } else {
                        echo '<div class="container">';
                            echo '<div class="nice-msg">There is No Items To Show</div>';
                            echo '<a href=\'items.php?do=add\' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>';
                        echo '</div>';
                    } ?>
            

            <?php
        } elseif ($do == 'add') { ?>

            <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="items.php?do=insert" method="POST">
                    <!--Start Name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" class="form-control"  placeholder="Name of the item" >
                        </div>
                    </div>
                    <!--End Name field-->
                    <!--Start Description field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" class="form-control"  placeholder="Description of your item" >
                        </div>
                    </div>
                    <!--End Description field-->
                    <!--Start Price field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" class="form-control"  placeholder="Price of your item" >
                        </div>
                    </div>
                    <!--End Price field-->
                    <!--Start Country made field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country" class="form-control" placeholder="Country of made" >
                        </div>
                    </div>
                    <!--End Country made field-->
                    <!--Start Status field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
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
                    <!--Start Members field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member">
                                <option value="0">..</option>
                                <?php 
                                    $allmembers = getAllFromAll("*" , "users" ,"" , "" , "UserID");
                                    /* Old wy to get all members and use the function
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt -> execute();
                                    $users = $stmt ->fetchAll();
                                    */
                                    foreach ($allmembers as $user) {
                                        echo "<option value=' " . $user['UserID'] . " '>" . $user['UserName']  . "</option>";
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--End Members field-->
                    <!--Start Category field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">
                                <option value="0">..</option>
                                <?php 
                                    $allcats = getAllFromAll("*" , "categories" ,"WHERE parent = 0" , "" , "ID");
                                    /*
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2 -> execute();
                                    $cats = $stmt2 ->fetchAll();
                                    */
                                    foreach ($allcats  as $category) {
                                        echo "<option value=' " . $category['ID'] . " '>" . $category['Name']  . "</option>";
                                        $childcats = getAllFromAll("*" , "categories" ,"WHERE parent = {$category['ID']}" , "" , "ID");
                                        foreach ($childcats as $child) {
                                            echo "<option value= '" . $child['ID'] . " '>---" . $child['Name'] . "</option>";
                                        }
                                    } 
                                ?>
                            </select>
                        </div>
                    </div>
                    <!--End Category field-->
                    <!--Start Tags  field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Tags</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="tags" class="form-control" placeholder="Sebrate Tags With Comma (,)" >
                            </div>
                        </div>
                    <!--End Tags  field-->
                    <!--Start Submit button-->
                    <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type='submit' value="Add Item" class="btn btn-primary btn-sm" />
                            </div>
                        </div>
                        <!--End Submit button-->
                </form>
            </div>

<?php
        } elseif ($do == 'insert') {

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Insert Item</h1>";
                    echo "<div class='container'>";

                    // Get Variables From The Form Which i will add to my DB
                    $name     = $_POST['name'];
                    $desc     = $_POST['description'];
                    $price    = $_POST['price'];
                    $country  = $_POST['country'];
                    $status   = $_POST['status'];
                    $member   = $_POST['member'];
                    $cat      = $_POST['category'];
                    $tags     = $_POST['tags'];

                    // Validate The Form
                    $forerrors = array();
                    if (empty($name)) {
                        $forerrors[] = "Name Can't Be Empty";
                    }
                    if (empty($desc)) {
                        $forerrors[] = "Description Can't Be Empty";
                    }
                    if (empty($price)) {
                        $forerrors[] = "Price Can't Be Empty";
                    }
                    if (empty($country)) {
                        $forerrors[] = "Country Can't Be Empty";
                    }
                    if ($status == 0 ) {
                        $forerrors[] = "You have determine the status";
                    }
                    if ($member == 0 ) {
                        $forerrors[] = "you must choose the member";
                    }
                    if ($cat == 0 ) {
                        $forerrors[] = "You have determine the Category";
                    }
                    // Loop for show errors 
                    foreach ($forerrors as $Error) {
                        
                        echo "<div class='alert alert-danger'>" . $Error . "</div>";
                    }
                    
                    // Check If There is No Errors Procceed update information
                    if (empty($forerrors)) {

                            // insert user info in database
                            $stmt = $con->prepare("INSERT INTO 
                                                items(Name , Description , Price , Country_Made , Status, Add_Date , Cat_ID , Member_id , tags)
                                                VALUES(:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztag )");
                            $stmt->execute(array(

                            'zname'     => $name,
                            'zdesc'     => $desc,
                            'zprice'    => $price,
                            'zcountry'  => $country,
                            'zstatus'   => $status,
                            'zcat'      => $cat,
                            'zmember'   => $member,
                            'ztag'      => $tags 
                            ));

                            // Echo Success Mes
                            echo '<div class="container">';
                                $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Inserted</div>';
                                redirectHome($themsg , 'back');
                            echo "</div>";
                        
                    }

                } else {

                    echo '<div class="container">';
                        $themsg = '<div class="alert alert-danger">You can\'t Browse this page direct</div>';
                        redirectHome($themsg);
                    echo "</div>";
                }
            
            echo "</div>";

        } elseif ($do == 'edit') {

            // Check if GET REQUEST item is number and get it's integer value

            $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; // if codition to see if id is exist or not

            // Select this id data 
            $stmt = $con->prepare("SELECT * FROM items where Item_ID = ? ");

            // Excute the data mean do the process
            $stmt->execute(array($itemid));

            // Featch (bring) the data
            $item = $stmt->fetch();

            // The item Count
            $count = $stmt->rowCount();

            // If there is id show the form to user
            if ($count > 0) { ?>

                <h1 class="text-center">Edit Item</h1>
                <div class="container">
                    <form class="form-horizontal" action="items.php?do=update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>" >
                        <!--Start Name field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control"  placeholder="Name of the item" value="<?php echo $item['Name']; ?>" >
                            </div>
                        </div>
                        <!--End Name field-->
                        <!--Start Description field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" class="form-control"  placeholder="Description of your item" value="<?php echo $item['Description']; ?>" >
                            </div>
                        </div>
                        <!--End Description field-->
                        <!--Start Price field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="price" class="form-control"  placeholder="Price of your item" value="<?php echo $item['Price']; ?>" >
                            </div>
                        </div>
                        <!--End Price field-->
                        <!--Start Country made field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="country" class="form-control" placeholder="Country of made" value="<?php echo $item['Country_Made']; ?>" >
                            </div>
                        </div>
                        <!--End Country made field-->
                        <!--Start Status field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Status</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="status">
                                    <option value="1" <?php if ($item['Status'] == 1) { echo "selected";} ?>>New</option>
                                    <option value="2" <?php if ($item['Status'] == 2) { echo "selected";} ?>>Like New</option>
                                    <option value="3" <?php if ($item['Status'] == 3) { echo "selected";} ?>>Mid</option>
                                    <option value="4" <?php if ($item['Status'] == 4) { echo "selected";} ?>>Old</option>
                                </select>
                            </div>
                        </div>
                        <!--End Status field-->
                        <!--Start Members field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Member</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="member">
                                    <?php 
                                        $stmt = $con->prepare("SELECT * FROM users");
                                        $stmt -> execute();
                                        $users = $stmt ->fetchAll();
                                        foreach ($users as $user) {
                                            echo "<option value=' " . $user['UserID'] . " '";  
                                                if ($item['Member_id'] == $user['UserID']) { echo "selected";}   
                                            echo ">" . $user['UserName']  . "</option>";
                                        } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--End Members field-->
                        <!--Start Category field-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10 col-md-6">
                                <select name="category">
                                    <option value="0">..</option>
                                    <?php 
                                        $stmt2 = $con->prepare("SELECT * FROM categories");
                                        $stmt2 -> execute();
                                        $cats = $stmt2 ->fetchAll();
                                        foreach ($cats  as $category) {
                                            echo "<option value=' " . $category['ID'] . " '";
                                                if ($item['Cat_ID'] == $category['ID']) { echo "selected";}
                                            echo ">" . $category['Name']  . "</option>";
                                        } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--End Category field-->
                        <!--Start Tags  field-->
                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Tags</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" name="tags" class="form-control" placeholder="Sebrate Tags With Comma (,)" value="<?php echo $item['tags']; ?>" >
                                </div>
                            </div>
                        <!--End Tags  field-->
                        <!--Start Submit button-->
                        <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type='submit' value="Save Item" class="btn btn-primary btn-sm" />
                                </div>
                            </div>
                            <!--End Submit button-->
                    </form>
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
                                                    item_id = ?");
                        $stmt->execute(array($itemid));
                        //Bring data as Rows
                        $rows = $stmt->fetchAll();

                    if (! empty($rows)) {
                    ?>

                    <h1 class="text-center">Manage [ <?php echo $item['Name']; ?> ] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                                foreach ($rows as $row ) {
                                    
                                    echo "<tr>";
                                        echo "<td>" . $row['comment'] . "</td>";
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
                <?php } ?>
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

                    echo "<h1 class='text-center'>Update Item</h1>";
                    echo "<div class='container'>";

                    // Get Variables From The Form
                    $id           = $_POST['itemid'];
                    $name         = $_POST['name'];
                    $description  = $_POST['description'];
                    $price        = $_POST['price'];
                    $country      = $_POST['country'];
                    $status       = $_POST['status'];
                    $category     = $_POST['category'];
                    $member       = $_POST['member'];
                    $tags       = $_POST['tags'];

                    // Validate The Form
                    $forerrors = array();
                    if (empty($name)) {
                        $forerrors[] = "Name Can't Be Empty";
                    }
                    if (empty($description)) {
                        $forerrors[] = "Description Can't Be Empty";
                    }
                    if (empty($price)) {
                        $forerrors[] = "Price Can't Be Empty";
                    }
                    if (empty($country)) {
                        $forerrors[] = "Country Can't Be Empty";
                    }
                    if ($status == 0 ) {
                        $forerrors[] = "You have determine the status";
                    }
                    if ($member == 0 ) {
                        $forerrors[] = "you must choose the member";
                    }
                    if ($category == 0 ) {
                        $forerrors[] = "You have determine the Category";
                    }
                    // Loop for show errors 
                    foreach ($forerrors as $Error) {
                        
                        echo "<div class='alert alert-danger'>" . $Error . "</div>";

                    }
                    
                    // Check If There is No Errors Procceed update information
                    if (empty($forerrors)) {

                        // Upadate the data base with this info

                        $stmt = $con->prepare("UPDATE
                                                    items 
                                                SET
                                                    Name = ? ,
                                                    Description = ? ,
                                                    Price = ? ,
                                                    Country_Made = ? ,
                                                    Status  = ? ,
                                                    Cat_ID = ? ,
                                                    Member_id = ? ,
                                                    tags = ?

                                                 WHERE
                                                  Item_ID = ?");
                        $stmt->execute(array($name , $description , $price , $country , $status , $category , $member , $tags , $id ));

                        // Echo Success Mes
                        echo '<div class="container">';
                        $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Updated</div>';
                        redirectHome($themsg , 'back');
                        echo "</div>";
                    }

                } else {
                    echo '<div class="container">';
                    $themsg = "<div class='alert alert-danger'>You can't Browse this page direct</div>";
                    redirectHome($themsg);
                    echo "</div>";
                }
            
            echo "</div>";


        } elseif ($do == 'delete') {

            echo "<h1 class='text-center'>Delete Member</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; // if condition to see if id is exist or not

                // Select this id data 

                $check = checkitems('Item_ID' , 'items' , $itemid);

                // If there is id show the form to user
                
                if ($check > 0) { 

                    $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
                    $stmt->bindParam(":zid" , $itemid);
                    $stmt->execute();

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Deleted</div>';
                    redirectHome($themsg , 'back');

            } else {

                $themsg = "<div class='alert alert-danger'>This Is Wrong ID</div>";
                redirectHome($themsg);
            }
            echo "</div>";


        } elseif ($do == 'approve') {

            echo "<h1 class='text-center'>Approve Item</h1>";
            echo "<div class='container'>";

                // Check if The ID is exist at the database and bring integer value
                $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0; // if condition to see if id is exist or not

                $check = checkitems('Item_ID' , 'items' , $itemid);
                
                if ($check > 0) { 

                    $stmt = $con->prepare("UPDATE Items SET Approve = 1 WHERE Item_ID = ?");
                    $stmt->execute(array($itemid)); // $itemid coming from request at approve button inside manage page

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Item Approved</div>';
                    redirectHome($themsg , 'back');

            } else {

                $themsg = "<div class='alert alert-danger'>This Is Wrong ID</div>";
                redirectHome($themsg);
            }
            echo "</div>";


        }




        include $temp . 'footer.php';

    } else {

    	header('Location: index.php');

    	exit();
    }

    ob_end_flush(); // Release The output