<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ../app/");
    exit;
}
// Include config file
require_once "../config.php";
// Define variables and initialize with empty values
$username = $password = $email = "";
$username_err = $password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, email FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set parameters
            $param_username = $username;
          	$param_email = $email;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $email);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                          	$_SESSION["email"] = $email;
                            // Redirect user to welcome page
                            header("location: ../app");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $_SESSION["businessname"]; ?> | Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://whatcode.dougbros.co.uk/pace.js"></script>
  <link rel="stylesheet" href="https://whatcode.dougbros.co.uk/pace.css">
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="https://legal.dougbros.co.uk"><?php echo $_SESSION["businessname"]; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="https://legal.dougbros.co.uk"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
      <?php if(isset($_SESSION["loggedin"])){ 
     	echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
        	 <ul class=\"dropdown-menu\">
         		 <li><a href=\"/actions?action=create\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;Create new case</a></li>
         		 <li><a href=\"/actions?action=open\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;&nbsp;Open case</a></li>
       		 </ul>
     	 </li>"; }; ?>
      <li><a href="#"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Help</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><?php if(isset($_SESSION["loggedin"])){ ?>
		<a href="/app"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); };?></a>
		<!--<?php //} elseif(!isset($_SESSION["loggedin"])) { ?>
        <a href="/signup"><span class="glyphicon glyphicon-user"></span> Sign Up</a><?php //}; ?>-->
          </li>
      <li class="active"><?php if(isset($_SESSION["loggedin"])){ ?>
        <a href="/app/admin/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
        <?php } elseif(!isset($_SESSION["loggedin"])) { ?>
        <a href="/login"><span class="glyphicon glyphicon-log-in"></span> Login</a><?php }; ?>
      </li>
    </ul>
  </div>
</nav>
  <?php 
  $username = $_GET['username'];
  ?>
<div class="container">
<h2>Login to the <?php echo $_SESSION["businessname"]; ?> portal</h2>
        	<p>Please fill in your credentials to login.</p>
        		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            		<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                		<label>Username</label>
                		<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                		<span class="help-block"><?php echo $username_err; ?></span>
            		</div>    
            		<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                		<label>Password</label>
                		<input type="password" name="password" class="form-control">
                		<span class="help-block"><?php echo $password_err; ?></span>
            		</div>
            		<div class="form-group">
                		<input type="submit" class="btn btn-primary button-shadow" value="Login">
            		</div>
            <p>Forgot your password? Reset it <a href="../reset">here</a>.</p>
        </form> 
</div>
</body>
</html>