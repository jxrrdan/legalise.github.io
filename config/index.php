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
	if ($stmt = $link->prepare("SELECT username, email, created_date FROM users WHERE username = ?")) {
		$stmt->bind_param('i', $id);

		 $stmt->execute();
		 $stmt->store_result();

	}

          	mysqli_stmt_bind_result($stmt, $username, $email, $created_date);
$results = "<table class='table table-hover'><tr><th>Class Code</th><th>Description</th><th>Location</th></tr>";
          while($test = mysqli_stmt_fetch($stmt)){
				$results .= "<tr>";
				$results .= "<td><a href='code?id=" . $userid . "&search=" . $userid . "'>" . $subscriptiontype . "</td>";
				$results .= "<td>" . $date_from . "</td>";
				$results .= "<td>" . $date_to . "</td>";
				$results .= "</tr>";

			}
			
			$results .= "</table>";
/* close connection */	
    $stmt->close();
		} 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $_SESSION["businessname"]; ?> | Configuration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://whatcode.dougbros.co.uk/pace.js"></script>
  <link rel="stylesheet" href="https://legal.dougbros.co.uk/pace.css">
  <style type="text/css">
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
  </style>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="https://legal.dougbros.co.uk"><?php echo $_SESSION["businessname"]; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="https://legal.dougbros.co.uk">Home</a></li>
      <?php if(isset($_SESSION["loggedin"])){ 
     	echo "<li class=\"dropdown active\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
        	 <ul class=\"dropdown-menu\">
         		 <li><a href=\"/actions?action=create\">Create new case</a></li>
         		 <li><a href=\"/actions?action=open\">Open case</a></li>
                 <li class=\"active\"><a href=\"/../config\">Configuration</a></li>
       		 </ul>
     	 </li>";
		}?>
      <li><a href="#">Help</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th"></span> Roles</a>
      <ul class="dropdown-menu">
        <li><a><?php echo $_SESSION["role"]; ?></a></li>
      </ul>
      </li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
      <ul class="dropdown-menu">
        <li><a><?php $email = $_SESSION['email']; echo $email; ?></a></li>
        <li><a href="admin/reset">Change password</a></li>
        <li><a href="admin/reset/email.php">Change email</a></li>
      </ul>
      </li>
      <li><a href="admin/logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<div class="subscriptions">
  <h1>Configuration</h1>
  <div class="custom-select">
  <form action="/app/subscriptions" name="subscription" method="GET">
    <select name="subscriptions" id="subscriptions" onchange="this.form.submit();">
      <option value="">Select One</option>
      <option value="per-c">Pay per code</option>
      <option value="month">Pay monthly</option>
      <option value="contr">Contribute</option>
    </select>
  </form>
  </div>
</div>
</div>
</body>
</html>