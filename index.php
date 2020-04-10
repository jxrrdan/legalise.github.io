<?php session_start(); 
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $_SESSION["businessname"]; ?> | Home</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://legal.dougbros.co.uk/pace.js"></script>
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
  </style>
</head>
<body>
<nav class="navbar navbar-inverse sticky">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="https://legal.dougbros.co.uk"><?php echo $_SESSION["businessname"]; ?></a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="https://legal.dougbros.co.uk"><span class="glyphicon glyphicon-home"></span>&nbsp;Home</a></li>
      <?php if(isset($_SESSION["loggedin"])){ 
     	echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
        	 <ul class=\"dropdown-menu\">
         		 <li><a href=\"/app/new\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;New case</a></li>
         		 <li><a href=\"/app\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;&nbsp;Open case</a></li>
       		 </ul>
     	 </li>";
		}?>
      <li><a href="#"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Help</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><?php if(isset($_SESSION["loggedin"])){ ?>
		<a href="/app"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); };?></a>
		<!--<?php //} elseif(!isset($_SESSION["loggedin"])) { ?>
        <a href="/signup"><span class="glyphicon glyphicon-user"></span> Sign Up</a><?php //}; ?>-->
          </li>
      <li><?php if(isset($_SESSION["loggedin"])){ ?>
        <a href="/app/admin/logout"><span class="glyphicon glyphicon-log-in"></span> Logout</a>
        <?php } elseif(!isset($_SESSION["loggedin"])) { ?>
        <a href="/login"><span class="glyphicon glyphicon-log-in"></span> Login</a><?php }; ?>
      </li>
    </ul>
  </div>
</nav>
<div class="container">
  <div style="color: red;"><?php 
  $logout_status = $_GET["logout"];
  if ($logout_status == "true") {
    echo "You sucessfully logged out.";}; ?></div>
  <div>
  <img src="https://via.placeholder.com/1100x60?text=Welcome+to+<?php echo $_SESSION["businessname"]?>" /><br />
    <b>What is Lorem Ipsum?</b><br /><p>
	Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
    when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
    It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. 
    It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, 
    and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
    </p>
    <b>Why do we use it?</b><br />
    <p>
	It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 
    The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here
      , content here', making it look like readable English. 
      Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, 
      and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, 
      sometimes by accident, sometimes on purpose (injected humour and the like).
    </p>
  </div>
</div>
</body>
</html>