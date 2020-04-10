<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login");
    exit;
}
require_once('../config.php'); 
if(isset($_SESSION["id"])) {
 		$id = $_SESSION["id"];
		if ($stmt = $link->prepare("SELECT username, email, created_date FROM users WHERE id = ?")) {
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->store_result();
	}
		} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $_SESSION["businessname"]; ?> | Portal</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://whatcode.dougbros.co.uk/pace.js"></script>
  <link rel="stylesheet" href="https://legal.dougbros.co.uk/pace.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style type="text/css">
 nav.sticky {
	position: -webkit-sticky; /* Safari */
	position: sticky;
	top: 0;
	z-index: 9999;
}  
nav.sticky::after {
  content: "";
  position: absolute;
  z-index: -1;
  bottom: -10px;
  /*left: 5%;*/
  height: 110%;
  width: 100%;
  opacity: 0.8;
  border-radius: 20px;
  display: block;
  
  /* Declaring our shadow color inherit from the parent (button) */
  background: inherit;
  
  /* Blurring the element for shadow effect */
  -webkit-filter: blur(6px);
  -moz-filter: blur(6px);
  -o-filter: blur(6px);
  -ms-filter: blur(6px);
  filter: blur(6px);
  
  /* Transition for the magic */
  -webkit-transition: all 0.2s;
  transition: all 0.2s;
}
    /* The container must be positioned relative: */
.custom-select {
  position: relative;
  font-family: Arial;
}

.custom-select select {
  display: none; /*hide original SELECT element: */
}

.select-selected {
  background-color: DodgerBlue;
}

/* Style the arrow inside the select element: */
.select-selected:after {
  position: absolute;
  content: "";
  top: 14px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: #fff transparent transparent transparent;
}

/* Point the arrow upwards when the select box is open (active): */
.select-selected.select-arrow-active:after {
  border-color: transparent transparent #fff transparent;
  top: 7px;
}

/* style the items (options), including the selected item: */
.select-items div,.select-selected {
  color: #ffffff;
  padding: 8px 16px;
  border: 1px solid transparent;
  border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
  cursor: pointer;
}

/* Style items (options): */
.select-items {
  position: absolute;
  background-color: DodgerBlue;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 99;
}

/* Hide the items when the select box is closed: */
.select-hide {
  display: none;
}

.select-items div:hover, .same-as-selected {
  background-color: rgba(0, 0, 0, 0.1);
}
th {
  background-color: DodgerBlue;
  color: white;
}

th, td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color: #f5f5f5;}

.wbrow {
	display: flex;
}
.wbcol {
	flex:50%;
	padding: 5px;
}
form.csrch input[type=text] {
  padding: 10px;
  font-size: 17px;
  border: 1px solid grey;
  float: left;
  width: 80%;
  background: #f1f1f1;
}

form.csrch button {
  float: left;
  width: 20%;
  padding: 10px;
  background: #2196F3;
  color: white;
  font-size: 17px;
  border: 1px solid grey;
  border-left: none;
  cursor: pointer;
}

form.csrch button:hover {
  background: #0b7dda;
}

form.csrch::after {
  content: "";
  clear: both;
  display: table;
}
  </style>
</head>
<body>
<nav class="navbar navbar-inverse sticky">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="https://legal.dougbros.co.uk"><?php echo $_SESSION["businessname"]; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="https://legal.dougbros.co.uk"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
      <?php if(isset($_SESSION["loggedin"])){ 
     	echo "<li class=\"dropdown active\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
        	 <ul class=\"dropdown-menu\">
         		 <li><a href=\"/app/new\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;New case</a></li>
         		 <li class=\"active\"><a href=\"/app\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;&nbsp;Open case</a></li>
       		 </ul>
     	 </li>";
		}?>
      <li><a href="#"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Help</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th"></span> Roles</a>
      <ul class="dropdown-menu">
        <?php $sql = "SELECT DISTINCT role_nar FROM `user_roles` A LEFT JOIN `roles` B ON A.role_cd=B.role_cd WHERE end_date >= CURRENT_DATE AND usr_id =" . $_SESSION['id'];
$result = $link->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<li><a>" . $row["role_nar"] . "</a></li>";
      //  $_SESSION["businessname"] = $row["bus_name"];
    }
} else {
   echo "<li><a>Err fetching roles</a></li>";
} ?>
      </ul>
      </li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
      <ul class="dropdown-menu">
        <li><a><?php $email = $_SESSION['email']; echo $email; ?></a></li>
        <li><a href="admin/reset">Change password</a></li>
        <li><a href="admin/reset/email.php">Change email</a></li>
      </ul>
      </li>
      <li><a href="admin/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<div class="wb">
  <h1>Workbench</h1>
  <div class="wbrow">
  <div class="wbcol">
  <h2>Recent cases</h2>
  <table><tr><th>Case ID</th><th>Customer</th><th>Last worked</th></tr>
  <?php $sql = "SELECT DISTINCT a.case_id, case when c.salut is null then 'Cannot get customer data' else c.salut end as salut, a.date_time FROM cms_recent_cas a LEFT OUTER JOIN case_ass_bp b ON a.case_id=b.case_id AND b.assoc_type='CUST' LEFT OUTER JOIN add_bp c ON b.bp_cd=c.bp_cd WHERE a.usr_id=" . $_SESSION['id'] . " AND a.case_id IN (SELECT DISTINCT case_id FROM `case`) ORDER BY id";
  $result = $link->query($sql);
  if ($result->num_rows > 0) {
  	while($row = $result->fetch_assoc()) {
  		echo "<tr><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["case_id"] . "</a></td><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["salut"] . "</a></td><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["date_time"] . "</a></td></tr>";
  	}
  } else {
  	echo "<td>No recent cases found.</td>";
  }
  ?>
  </table>
  </div>
  <div class="wbcol">
  <h2>Case search</h2>
  	<form class="csrch" action="" method="post">
  		<input type="text" placeholder="<?php if(empty($_POST["search"])) { echo "Search...";
  		 } else {
  		 echo $_POST["search"];
  		 }
  		 ?>" name="search">
  		<button type="post"><i class="fa fa-search"></i></button>
	</form>
	<?php
    if(empty($_POST["search"])) {
      echo "Please enter a search term above to find a case.";
    } else {
	if(isset($_POST["search"])) {
	$srch_trm = "%" . $_POST["search"] . "%";
	$sql = "SELECT DISTINCT a.case_id, case when c.title is null then 'Cannot get customer data' else c.title end as title, c.nam, c.nam2, case when d.add_li_1 is null then 'Cannot get customer data' when char_length(d.add_li_1) < 5 then concat(d.add_li_1,', ',d.add_li_2) else d.add_li_1 end as add_li_1, case when d.postcode is null then 'Cannot get customer data' else d.postcode end as postcode FROM `case` a LEFT JOIN case_ass_bp b ON a.case_id=b.case_id AND b.assoc_type='CUST' LEFT JOIN add_bp c ON b.bp_cd=c.bp_cd LEFT JOIN addresses d ON c.bp_cd=d.bp_cd WHERE (a.case_id LIKE '" . $srch_trm . "' OR c.salut LIKE '" . $srch_trm . "' OR d.postcode LIKE '" . $srch_trm . "') ORDER BY a.case_id asc";
  	$result = $link->query($sql);
  	if ($result->num_rows > 0) {
  	  	echo "<table><tr><th>CaseID</th><th>Customer</th><th>Address</th><th>Postcode</th>";
  		while($row = $result->fetch_assoc()) {
		echo "<tr><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["case_id"] . "</a></td><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["title"] . " " . $row["nam"] . " " . $row["nam2"] . "</a></td><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["add_li_1"] . "</a></td><td><a href='\app\case?caseid=" . $row["case_id"] . "'>" . $row["postcode"] . "</a></td></tr>";
  	}
		echo "</table>";
  	} else {
  	echo ("No case found using " . $_POST["search"] . " as a term. You can search using case ID, Salutation or Postcode." . mysqli_error($link));
  	}
  	} else {
  	echo "Enter a search term above to find a case.";
  	}
    }
  	?>
  </div>
  </div>
</div>
</div>
</body>
</html>