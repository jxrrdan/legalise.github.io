<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
header("location: ../login");
exit;
}
require_once('../../config.php'); 
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
    <title>
      <?php echo $_SESSION["businessname"]; ?> | New Case
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
    </script>
    <script src="https://whatcode.dougbros.co.uk/pace.js">
    </script>
    <link rel="stylesheet" href="https://legal.dougbros.co.uk/pace.css">
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
        display: none;
        /*hide original SELECT element: */
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
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
      }
      tr:hover {
        background-color: #f5f5f5;
      }
      input[type=text], input[type=date], input[type=number], input[type=email] {
  		padding: 12px 20px;
  		margin: 5px 0 22px 0;
  		border: none;
 		background: #f1f1f1;
 		transition: width 0.4s ease-in-out;
     }
		input[type=text]:focus, input[type=date]:focus, input[type=date]:number, input[type=date]:email {
	  	background-color: #ddd;
  		outline: none;
  		width: 100%;
	}
      select {
	width: 100%;
	padding: 16px 20px;
	margin: 5px 0 22px 0;
	border: none;
	border-radius: 4px;
	background-color: #f1f1f1;
	}
      div.container::after {
margin-bottom: 15px;
}
    </style>
  </head>
  <body>
    <nav class="navbar navbar-inverse sticky">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="https://legal.dougbros.co.uk">
            <?php echo $_SESSION["businessname"]; ?>
          </a>
        </div>
        <ul class="nav navbar-nav">
          <li>
            <a href="https://legal.dougbros.co.uk"><span class="glyphicon glyphicon-home"></span>&nbsp;Home
            </a>
          </li>
          <?php if(isset($_SESSION["loggedin"])){ 
echo "<li class=\"dropdown active\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
<ul class=\"dropdown-menu\">
<li class=\"active\"><a href=\"/app\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;New case</a></li>
<li><a href=\"/app\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;&nbsp;Open case</a></li>
</ul>
</li>";
}?>
          <li>
            <a href="#"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Help
            </a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <span class="glyphicon glyphicon-th">
              </span> Roles
            </a>
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
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
              <span class="glyphicon glyphicon-user">
              </span> 
              <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </a>
            <ul class="dropdown-menu">
              <li>
                <a>
                  <?php $email = $_SESSION['email']; echo $email; ?>
                </a>
              </li>
              <li>
                <a href="admin/reset">Change password
                </a>
              </li>
              <li>
                <a href="admin/reset/email.php">Change email
                </a>
              </li>
            </ul>
          </li>
          <li>
            <a href="admin/logout">
              <span class="glyphicon glyphicon-log-out">
              </span> Logout
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
      <div class="">
        <h1>New Case
        </h1>
        <div class="">
          <h3>Customer Details
          </h3>
          <form action="/app/new/create/" method="POST">
            <select placeholder="Title" name="title" required>
            <?php
            $sql = "SELECT code_id, code_nar FROM codes WHERE code_type='TITLE' AND not_in_use !='1' ORDER BY code_nar";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["code_id"] . "'>" . $row["code_nar"] . "</option>";
            }
            echo "<option value='' disabled selected>Title</option>";
            } else {
            echo "<option value='' disabled selected>Error fetching TITLE</option>";
            }
            ?>
            </select><br>
            <input type="text" maxlength="50" placeholder="Firstname" name="nam" size="50" required><br>
            <input type="text" maxlength="50" placeholder="Surname" name="nam2" size="50" required><br>
            <input type="date" placeholder="Date of birth" name="dateofbirth" required><br>
            <h3>Contact Details</h3>
            <select placeholder="Address Type" name="adtyp" required>
            <?php
            $sql = "SELECT code_id, code_nar FROM codes WHERE code_type='ADTYP' AND not_in_use !='1' ORDER BY code_nar";
            $result = $link->query($sql);
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row["code_id"] . "'>" . $row["code_nar"] . "</option>";
            }
            echo "<option value='' disabled selected>Address Type</option>";
            } else {
            echo "<option value='' disabled selected>Error fetching ADTYP</option>";
            }
            ?>
            </select><br>
            <input type="text" maxlength="50" placeholder="Address Line 1" name="add_li_1" size="50" required><br>
            <input type="text" maxlength="50" placeholder="Address Line 2" name="add_li_2" size="50" ><br>
			<input type="text" maxlength="50" placeholder="Address Line 3" name="add_li_3" size="50" ><br>
			<input type="text" maxlength="50" placeholder="Address Line 4" name="add_li_4" size="50" ><br>
            <input type="text" maxlength="50" placeholder="Address Line 5" name="add_li_5" size="50" ><br>
            <input type="text" placeholder="Postcode" name="postcode" size="10" maxlength="8" required><br>
            <input type="number" maxlength="12" placeholder="Mobile" name="mobile" size="50" required><br>
            <input type="number" maxlength="12" placeholder="Telephone" name="telephone" size="50"><br>
            <input type="email" maxlength="100" placeholder="Email" name="email" size="50" required><br>
            <input type="submit">
            Create case
          </form>
        </div>
      </div>
    </div>
  </body>
</html>