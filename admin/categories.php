<?php


	/*
	====================================

	== Categories Page

	====================================
	*/

	ob_start(); // Output Beffering Start

	session_start();

	$pagetiltle = 'Categories';

    if (isset($_SESSION['Username'])) {

        include 'init.php';


        $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

        if ($do == 'manage') {

            $sort = 'ASC';

            $sort_array = array('ASC' , 'DESC');

            if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

                $sort = $_GET['sort'];
            }

            $stmt2 = $con->prepare("SELECT * From categories WHERE Parent = 0 ORDER BY Ordering $sort");

            $stmt2->execute();

            $cats = $stmt2->fetchAll(); 

?>

            <h1 class="text-center">Categories</h1>
            <?php
            if (! empty($cats)) { ?>
            <div class="container categories">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-edit"></i> Mange Categories
                        <div class="option pull-right">
                            <i class="fa fa-sort"></i>Ordering :[
                            <a class="<?php if ($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a>  |
                            <a class="<?php if ($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
                            <i class="fa fa-eye"></i>View: [
                            <span class="active" data-view="classic">Classic</span> |
                            <span data-view="full">Full</span> ]
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php
                            foreach($cats as $cat) {
                                echo "<div class='cat'>";
                                    echo "<div class='hidden-btns'>";
                                        echo "<a href='categories.php?do=edit&catid=". $cat['ID'] . " ' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
                                        echo "<a href='categories.php?do=delete&catid=". $cat['ID'] . " ' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
                                    echo "</div>";
                                    echo "<h3>" . $cat['Name'] . "</h3>";
                                    echo "<div class='full-view'>";
                                         echo "<p>"; if($cat['Description'] == '') {echo "This is Cateegory hasn't Description";} else { echo $cat['Description']; } echo "</p>";
                                         if ($cat['Visiblity'] == 1 ) {echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';}
                                         if ($cat['Allow_Comment'] == 1 ) {echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';}
                                         if ($cat['Allow_Ads'] == 1 ) {echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';}
                                    echo "</div>";
                                    /*Get Child Categories*/
                                        $childCats = getAllFromAll("*" , "categories" ,"WHERE parent = {$cat['ID']} " , "" , "ID" , "ASC");
                                        if (! empty ($childCats)) {
                                            echo '<h4 class="child-head">Child Categories</h4>';
                                            echo '<ul class="list-unstyled child-cats">';
                                            foreach ($childCats as $c) {
                                                echo "<li class='child-link'>
                                                    <a href='categories.php?do=edit&catid=". $c['ID'] ."'>" . $c['Name'] . "</a>
                                                    <a href='categories.php?do=delete&catid=". $c['ID'] . " ' class='show-delete confirm'> Delete</a>
                                                </li>";
                                            }  
                                            echo '</ul>';
                                        }
                                    echo "</div>";
                                    echo "<hr>";
                                }
                                
                ?>
                    </div>
                </div>
                <a class="add-category btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i> Add New Category</a>
            </div>
            <?php } else {
                        echo '<div class="container">';
                            echo '<div class="nice-msg">There is No Categories To Show</div>';
                            echo '<a class="add-category btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i> Add New Category</a>';
                        echo '</div>';
            }?>
        

    <?php
        } elseif ($do == 'add') {
    ?>


            <h1 class="text-center">Add New Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=insert" method="POST" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="name" class="form-control" autocomplete="off" required="required" placeholder="Category Name" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="description" class="form-control" placeholder="Descripe the category" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Ordering</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="ordering" class="form-control" placeholder="Number to orrang Categories"/>
                            </div>
                        </div>
                        <!--start Category type -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Parent ?</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php
                                        $allCats= getAllFromAll("*", "categories" ,"WHERE parent = 0", "", "ID" , "ASC");
                                        foreach ($allCats as $cat) {
                                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . '</option>';
                                        }
                                     ?>

                                </select>
                            </div>
                        </div>
                        <!--End Category type -->
                        <!-- Start visiblity feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Visible</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" />
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End visiblity feild-->

                        <!-- Start Commenting feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Allow Commenting</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" />
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Commenting feild-->

                        <!-- Start Ads feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Allow Ads</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Ads feild-->
                        

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type='submit' value="Add Category" class="btn btn-primary btn-lg" />
                            </div>
                        </div>
                    </form>
                </div>

        <?php

        } elseif ($do == 'insert') {


                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    echo "<h1 class='text-center'>Update Category</h1>";
                    echo "<div class='container'>";

                    // Get Variables From The Form Which i will add to my DB
                    $name       = $_POST['name'];
                    $desc       = $_POST['description'];
                    $parent     = $_POST['parent'];
                    $order      = $_POST['ordering'];
                    $visible    = $_POST['visibility'];
                    $comment    = $_POST['commenting'];
                    $ads        = $_POST['ads'];
                    

                    // Check if the info is repeted or not 
                    $check = checkitems("Name" , "Categories" , $name);
                    if ($check == 1) {

                        $themsg =  "<div class='alert alert-danger'>Sorry This Category Is Exist</div>";
                        redirectHome($themsg , 'back');
                    } else {
                        // insert Category info in database
                        $stmt = $con->prepare("INSERT INTO 
                                            Categories(Name , Description , parent ,Ordering , Visiblity , Allow_Comment,   Allow_Ads)
                                            VALUES(:zname , :zdesc , :zparent , :zorder , :zvisible , :zcomment , :zads)
                                                ");
                        $stmt->execute(array(
                        // '$var as i made at mysql'  => '$var as i named values from form'
                        'zname'     => $name,
                        'zdesc'     => $desc,
                        'zparent'   => $parent,
                        'zorder'    => $order,
                        'zvisible'  => $visible,
                        'zcomment'  => $comment,
                        'zads'      => $ads
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
                        redirectHome($themsg , 'back');
                    echo "</div>";
                }
                

        } elseif ($do == 'edit') {

            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; // if codition to see if Cat id is exist or not

            // Select this id data 
            $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");

            // Excute the data mean do the process
            $stmt->execute(array($catid));

            // Featch (bring) the data
            $cat = $stmt->fetch();

            // The row Count
            $count = $stmt->rowCount();

            // If there is id show the form to user
            if ($count > 0) { ?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=update" method="POST" >
                        <input type="hidden" name="catid" value="<?php echo $catid ?>" >
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Name</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="name" class="form-control" required="required" placeholder="Category Name" value="<?php echo $cat['Name'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Description</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="description" class="form-control" placeholder="Descripe the category" value="<?php echo $cat['Description'] ?>" />
                            </div>
                        </div>
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Ordering</label>
                            <div class="col-sm-10 col-md-4">
                                <input type='text' name="ordering" class="form-control" placeholder="Number to orrang Categories" value="<?php echo $cat['Ordering'] ?>"/>
                            </div>
                        </div>
                        <!--start Category type -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Parent ?</label>
                            <div class="col-sm-10 col-md-4">
                                <select name="parent">
                                    <option value="0">None</option>
                                    <?php
                                        $allCats= getAllFromAll("*", "categories" ,"WHERE parent = 0", "", "ID" , "ASC");
                                        foreach ($allCats as $c) {
                                            echo "<option value='" . $c['ID'] . "'";

                                            if ($cat['parent'] == $c['ID']) {
                                                echo 'selected';
                                            }

                                            echo ">" . $c['Name'] . '</option>';
                                        }
                                     ?>

                                </select>
                            </div>
                        </div>
                        <!--End Category type -->
                        <!-- Start visiblity feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Visible</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visiblity'] == 0 ) {echo 'checked';} ?> />
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visiblity'] == 1 ) {echo 'checked';} ?> />
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div><!-- End visiblity feild-->
                        <!-- Start Commenting feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Allow Commenting</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0 ) {echo 'checked';} ?> />
                                    <label for="com-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1 ) {echo 'checked';} ?> />
                                    <label for="com-no">No</label>
                                </div>
                            </div>
                        </div><!-- End Commenting feild-->
                        <!-- Start Ads feild-->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 lable-control">Allow Ads</label>
                            <div class="col-sm-10 col-md-4">
                                <div>
                                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0 ) {echo 'checked';} ?> />
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1 ) {echo 'checked';} ?> />
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div><!-- End Ads feild-->

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

            echo "<h1 class='text-center'>Update Categories</h1>";
            echo "<div class='container'>";


             if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    // Get Variables From The Form
                    $id         = $_POST['catid'];
                    $name       = $_POST['name'];
                    $desc       = $_POST['description'];
                    $order      = $_POST['ordering'];
                    $parent     = $_POST['parent'];
                    $visible    = $_POST['visibility'];
                    $comment    = $_POST['commenting'];
                    $ads        = $_POST['ads'];


                    $stmt = $con->prepare("UPDATE 
                                                categories
                                            SET 
                                                Name = ? ,
                                                Description = ? ,
                                                Ordering = ? ,
                                                parent = ? ,
                                                Visiblity = ? ,
                                                Allow_Comment =? ,
                                                Allow_Ads = ? 
                                            WHERE 
                                                Id = ?");

                    $stmt->execute(array($name , $desc , $order , $parent , $visible , $comment , $ads , $id));

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

        } elseif ($do == 'delete') {

             echo "<h1 class='text-center'>Delete Category</h1>";
             echo "<div class='container'>";

                // Check if  catiD is exist at the database and bring integer value
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0; // if condition to see if id is exist or not

                // Select this id data 
                // $stmt = $con->prepare("SELECT * FROM users where Userid = ? LIMIT 1");

                $check = checkitems('ID' , 'categories' , $catid);

                // If there is id show the form to user
                
                if ($check > 0) { 

                    $stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
                    $stmt->bindParam(":zid" , $catid);
                    $stmt->execute();

                    $themsg = "<div class='alert alert-success'>" .  $stmt->rowCount() . ' ' . 'Record Deleted</div>';
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