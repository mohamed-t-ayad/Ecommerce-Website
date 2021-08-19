<?php

//Start Front end function

	

	/*
	** Get All Function V 2.1 to make most functions purpose
	** Function To make any thing 
	*/

	function getAllFromAll($feild , $table , $where = NULL , $and= NULL , $orderBy , $ordering = 'DESC') {

		global $con	;

		$getAll = $con->prepare("SELECT $feild FROM $table $where $and ORDER BY $orderBy $ordering");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;
	}






	/*
	** Get All Function V 1.0
	** Function To Get All Feilds  From any table
	*/

	function getAllFrom($tableName , $orderBy , $where) {

		global $con	;

		//$sql = $where == NULL ? '' : $where;

		$getAll = $con->prepare("SELECT * FROM $tableName $sql ORDER BY $orderBy DESC");

		$getAll->execute();

		$all = $getAll->fetchAll();

		return $all;
	}






	/*
	** Function To Check if Data is Exist at Database or not V 1.0
	** Function Accept parametrs 
	** $select ==> The item to select ex -> user , item , category
	** $from ==> The table to select From ex -> users , categories , items
	** $value ==> The value of select  ex-> osama , books , electronics
	*/

	function checkitems($select , $from , $value) {

		global $con; // To use var at any place in any function

		$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

		$statment->execute(array($value));

		$count = $statment->rowCount();

		return $count;
	}


	
	/*
	** Get Categories Function V 1.0
	** Function To Get Categories From dtabase

	***************** Now i don't need to have this function gatAllFromAll work over it

	*

	function getCat() {

		global $con	;

		$getcat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");

		$getcat->execute();

		$cats = $getcat->fetchAll();

		return $cats;
	}

	/*
	** Get Ad ITEMS Function V 2.0
	** Function To Get AD Categories From dtabase
	*/

	function getItems($where , $value, $Approve = NULL) {

		global $con	;

		/*$sql = $Approve == NULL ? 'AND Approve = 1' : '';*/

		/*  the same if but with abrevation */
		if ($Approve == NULL) {
			$sql = 'AND Approve = 1';
		} else {
			$sql = NULL;
		}
		

		$getitems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY Item_ID DESC");

		$getitems->execute(array($value));

		$items = $getitems->fetchAll();

		return $items;
	}





	/*
	** Check if user is activated
	** Function To check the regstatus of user
	*/

	function checkuserstatus ($user) {

		global $con;

        $stmtx = $con->prepare("SELECT 
                                    Username, RegStatues 
                                from 
                                    users 
                                where 
                                    Username = ? 
                                AND 
                                    RegStatues = 0 
                                ");
        $stmtx->execute(array($user));
        $status = $stmtx->rowCount();

        return $status;
	}
















	// The back end functions

	/*
		** Title function that echo the page tittle  V 1.0
		*** make var $pagetiltle if exist will de shown if not exist will echo default tittle
	*/

	function gettitle() {

		global $pagetiltle;

		if (isset($pagetiltle)) {

			echo $pagetiltle;
		} else {

			echo lang('DEFAULT');
		}
	}

	/*
	** Home Redirect Function V 2.0
	** Parametrs ==> The message ex [error , success, warning]
	** 				 $url => The link which will be redirected to
	** 				 $seconds ==> Time to redirect to home
	*/

	function redirectHome($themsg , $url = null , $seconds = 3) {

			if ($url === null) {

				$url = 'index.php';

			} else {
				// if condition oneline code
				$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
			}
			
				/*
				** upper if but with long if

					if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

						$url = $_SERVER['HTTP_REFERER'];

					} else {

						$url = 'index.php';
					}
				}	
				*/
			echo $themsg;

			echo "<div class='alert alert-info'>You will Be Redirected After $seconds seconds :)</div>";

			header("refresh:$seconds; url=$url");

			exit();

	}



	/* Ayad Function to calculate the number of recoeds from database
		** IT still doesn't woek at lesson 44 
	*/
	function ayad($col , $from , $val = NULL) {
		global $con;
		$sub ='';
		if ($val !== NULL) {
			$sub ="WHERE $col = ?";
		}
		
		$stmt = $con->prepare("SELECT $col FROM $from WHERE $sub");
		$stmt->execute(array($val));
		$count = $stmt->rowCount();

		return $count;

	}

	/*
	** Count Number Of Items Function V 1.0
	** Function To Count Number OF Items Rows
	** $item = The item count
	** $table = The table to choose from
	*/

	function countitems($item ,$table) {
		global $con;
		$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt2->execute();

        return $stmt2->fetchColumn();
	}
	

	/*
	** Get Latest Records Function V 1.0
	** Function To Get Latest ITems From dtabase
	** $select ==> Field to select
	** $table  ==> The Table where i Choose From
	** $order  ==> The column which used to order with
	** $limit ==> Numbers of records you will get
	*/

	function getlatest($select , $table , $order , $limit ) {

		global $con	;

		$getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

		$getstmt->execute();

		$rows = $getstmt->fetchAll();

		return $rows;
	}
