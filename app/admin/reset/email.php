<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../../login/index.php");
    exit;
}
 
// Include config file
require_once "../../../config.php";
 
// Define variables and initialize with empty values
$new_email = $email = $confirm_email = "";
$new_email_err = $email_err = $confirm_email_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new email
    if(empty(trim($_POST["new_email"]))){
        $new_email_err = "Please enter the new email.";    
    } else{
        $new_email = trim($_POST["new_email"]);
    }
    
    // Validate confirm email
    if(empty(trim($_POST["confirm_email"]))){
        $confirm_email_err = "Please confirm the email.";
    } else{
        $confirm_email = trim($_POST["confirm_email"]);
        if(empty($new_email_err) && ($new_email != $confirm_email)){
            $confirm_email_err = "Email did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_email_err) && empty($confirm_email_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_email, $param_id);
            
            // Set parameters
            $param_email = $new_email;
            $param_id = $_SESSION["id"];
          	$new_email = $_SESSION["email"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Email updated successfully. Redired to regular landing page
                header("location: ../../../app/index.php");
                exit();
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
  <title>Whatcode? | Portal Settings</title>
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
      <a class="navbar-brand" href="https://whatcode.dougbros.co.uk">Whatcode?</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="https://whatcode.dougbros.co.uk">Home</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Services <span class="caret"></span></a>
         <ul class="dropdown-menu">
          <li><a href="../../../service?service=per-code">Pay per code</a></li>
          <li><a href="../../../service?service=monthly">Subscription</a></li>
          <li><a href="../../../service?service=contribute">Help us</a></li>
        </ul>
      </li>
      <li><a href="#">About Us</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown active"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
      <ul class="dropdown-menu">
        <li><a href="../reset">Change password</a></li>
        <li class="active"><a href="#">Change email</a></li>
      </ul>
      </li>
      <li><a href="admin/logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container">
        <h2>Reset Email</h2>
        <p>Please fill out this form to reset your email.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group <?php echo (!empty($new_email_err)) ? 'has-error' : ''; ?>">
                <label>New Email</label>
                <input type="email" name="new_email" class="form-control" value="<?php echo $new_email; ?>">
                <span class="help-block"><?php echo $new_email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_email_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Email</label>
                <input type="email" name="confirm_email" class="form-control">
                <span class="help-block"><?php echo $confirm_email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary button-shadow" value="Submit">
                <a class="btn btn-link" href="../../">Cancel</a>
            </div>
        </form>
</div>
</body>
</html>