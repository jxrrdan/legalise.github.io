<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
header("location: ../../login");
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
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $CurrentURL= "https"; 
	else
    $CurrentURL = "http"; 
	$CurrentURL .= "://"; 
	$CurrentURL .= $_SERVER['HTTP_HOST']; 
	$CurrentURL .= $_SERVER['REQUEST_URI']; 
/*// Get username to store create note.
$sql_un = "SELECT username FROM users WHERE id=" . $_SESSION["id"];
$result_un = $link->query($sql_un);
if ($result_un->num_rows > 0) {
while($row = $result_un->fetch_assoc())	{
$i_un = $row["username"];
}
} else {
$i_un = "SYSTEM";
}
// Get next mysql IDs if cannot set as 9999999 as exception. From table config.
$sql_ids = "SELECT max(last_case_id)+1 next_case_id, max(last_bp_id)+1 next_bp_id FROM config";
$result_ids = $link->query($sql_ids);
if ($result_ids->num_rows > 0) {
while($row = $result_ids->fetch_assoc())	{
$i_next_bp_id = $row["next_bp_id"];
$i_next_case_id = $row["next_case_id"];
}
} else {
$i_next_bp_id = 9999999;
$i_next_case_id = 9999999;
}
// Get TITLE narrative codes if cannot get set as NA. From table codes.
$sql_title = "SELECT code_nar FROM codes WHERE code_type='TITLE' AND code_id='" . $_POST["title"] . "'";
$result_title = $link->query($sql_title);
if ($result_title->num_rows > 0) {
while($row = $result_title->fetch_assoc())	{
$i_title = $row["code_nar"];
}
} else {
$i_title = "NA";
}
$i_nam = $_POST["nam"];
$i_nam2 = $_POST["nam2"];
$i_dob = $_POST["dateofbirth"];
$i_adtyp = $_POST["adtyp"];
$i_add_li_1 = $_POST['add_li_1'];
$i_add_li_2 = $_POST['add_li_2'];
$i_add_li_3 = $_POST['add_li_3'];
$i_add_li_4 = $_POST['add_li_4'];
$i_add_li_5 = $_POST['add_li_5'];
$i_postcode = $_POST['postcode'];
$i_mobile = $_POST['mobile'];
$i_telephone = $_POST['telephone'];
$i_email = $_POST['email'];
$i_salut = $i_title . " " . substr($i_nam, 0, 1) . " " . $i_nam2;
// Get default case status from config.
$sql_case_status = "SELECT dft_case_stat FROM config";
$result_case_status = $link->query($sql_case_status);
if ($result_case_status->num_rows > 0) {
while($row = $result_case_status->fetch_assoc()) {
$i_dft_case_status = $row["dft_case_stat"];
}
} else {
$i_dft_case_status = 99;
}
// Get default address type from config.
$sql_dft_add = "SELECT code_id FROM codes INNER JOIN config on codes.code_id=config.dft_add_typ WHERE codes.code_type='ADTYP'";
$result_dft_add = $link->query($sql_dft_add);
if ($result_dft_add->num_rows > 0) {
while($row = $result_dft_add->fetch_assoc())	{
$i_dft_add = $row["code_id"];
}
} else {
$i_dft_add = 'CORR';
}
// Get default association type from config.
$sql_dft_asoc = "SELECT dft_assoc_typ FROM config";
$result_dft_asoc = $link->query($sql_dft_asoc);
if ($result_dft_asoc->num_rows > 0) {
while($row = $result_dft_asoc->fetch_assoc())	{
$i_dft_asoc = $row["dft_assoc_typ"];
}
} else {
$i_dft_asoc = 'CUST';
}
// After data is prepared ^^ above ^^ commit to database in the 6 statements below. Starting with updating the next case id if a new case is being created.
$update_id_sql = "UPDATE config SET last_case_id = " . $i_next_case_id . ", last_bp_id = " . $i_next_bp_id;
if($link->query($update_id_sql) === true)  {
} else {
echo "error executing " . $update_id_sql . " " . mysqli_error($link);
}
// Store business partner
$add_bp_sql = "INSERT INTO `add_bp` (bp_cd, nam, nam2, title, salut, dateofbirth, email, created_date, start_date)
VALUES ('" . $i_next_bp_id . "', '" . $i_nam . "', '" . $i_nam2 . "', '" . $i_title . "', '" . $i_salut . "', '" . $i_dob . "', '" . $i_email . "', CURRENT_DATE, CURRENT_DATE)";
if($link->query($add_bp_sql) === true)  {
} else {
echo "error executing " . $add_bp_sql . " " . mysqli_error($link);
}
// Store address
$addresses_sql = "INSERT INTO `addresses` (bp_cd, adtyp, add_li_1, add_li_2, add_li_3, add_li_4, add_li_5, postcode, mobile, telephone)
VALUES ('" . $i_next_bp_id . "', '" . $i_dft_add . "', '" . $i_add_li_1 . "', '" . $i_add_li_2 . "', '" . $i_add_li_3 . "', '" . $i_add_li_4 . "', '" . $i_add_li_5 . "', '" . $i_postcode . "', '" . $i_mobile . "', '" . $i_telephone . "')";
if($link->query($addresses_sql) === true)  {
} else {
echo "error executing " . $addresses_sql . " " . mysqli_error($link);
}
// Store case
$create_case_sql = "INSERT INTO `case` (case_id, case_stat, created_date, created_usr, last_updated_usr)
VALUES ('" . $i_next_case_id . "', '" . $i_dft_case_status . "', CURRENT_DATE, '" . $_SESSION["id"] . "', '" . $_SESSION["id"] . "')";
if($link->query($create_case_sql) === true)  {
} else {
echo "error executing " . $create_case_sql . " " . mysqli_error($link);
}
// Store associated business partners
$create_assoc_sql = "INSERT INTO `case_ass_bp` (case_id, assoc_type, bp_cd)
VALUES ('" . $i_next_case_id . "', '" . $i_dft_asoc . "', '" . $i_next_bp_id . "')";
if($link->query($create_assoc_sql) === true)  {
} else {
echo "error executing " . $create_assoc_sql . " " . mysqli_error($link);
}
// Store creation of note
$create_note_record = "INSERT INTO `case_notes` (case_id, note_id, usr_id, note, date_time)
VALUES ('" . $i_next_case_id . "', '1', '" . $_SESSION["id"] . "', 'Case created by " . $i_un . ".', CURRENT_TIMESTAMP)";
if($link->query($create_note_record) === true)  {
} else {
echo "error executing " . $create_note_record . " " . mysqli_error($link);
}
// Store recent case history function (multiple queries are required to limit case history to 10)
// Start by updating all current recent cases by adding id + 1 - ordered by id rather than date/time
$update_recent_cas = "UPDATE cms_recent_cas SET id = id + 1 WHERE usr_id='" . $_SESSION["id"] . "'";
if($link->query($update_recent_cas) === true) {
} else {
echo "error executing " . $update_recent_cas . " " . mysqli_error($link);
}
// Create recent cases record
$create_recent_cas = "INSERT INTO `cms_recent_cas` (case_id, date_time, usr_id, id) VALUES ('" . $i_next_case_id . "', CURRENT_TIMESTAMP, '" . $_SESSION["id"] . "', '1')";
if($link->query($create_recent_cas) === true) {
} else {
echo "error executing " . $create_recent_cas . " " . mysqli_error($link);
}
// Delete case records over >10
$delete_recent_cas = "DELETE FROM `cms_recent_cas` WHERE id > 10";
if($link->query($delete_recent_cas) === true) {
//header("location: ../../../app");
//exit;
} else {
echo "error executing " . $delete_recent_cas . " " . mysqli_error($link);
}
*/
?>
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login");
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
if(isset($_GET["caseid"])) {
	$_SESSION["caseidopen"] = $_GET["caseid"];
	header("location: ../case/");
	exit;
	} else {
	}
if(isset($_SESSION["caseidopen"])) {
	$case_id = $_SESSION["caseidopen"];
} else {
}
// Get default address type from config.
$sql_dft_add = "SELECT code_id FROM codes INNER JOIN config on codes.code_id=config.dft_add_typ WHERE codes.code_type='ADTYP'";
$result_dft_add = $link->query($sql_dft_add);
if ($result_dft_add->num_rows > 0) {
while($row = $result_dft_add->fetch_assoc())	{
$i_dft_add = $row["code_id"];
}
} else {
$i_dft_add = 'CORR';
}
// Get default association type from config.
$sql_dft_asoc = "SELECT dft_assoc_typ FROM config";
$result_dft_asoc = $link->query($sql_dft_asoc);
if ($result_dft_asoc->num_rows > 0) {
while($row = $result_dft_asoc->fetch_assoc())	{
$i_dft_asoc = $row["dft_assoc_typ"];
}
} else {
$i_dft_asoc = 'CUST';
}
// Store recent case history function (multiple queries are required to limit case history to 10)
// Start by updating all current recent cases by adding id + 1 - ordered by id rather than date/time
$update_recent_cas = "UPDATE cms_recent_cas SET id = id + 1 WHERE usr_id='" . $_SESSION["id"] . "'";
if($link->query($update_recent_cas) === true) {
} else {
echo "error executing " . $update_recent_cas . " " . mysqli_error($link);
}
// Create recent cases record
$create_recent_cas = "INSERT INTO `cms_recent_cas` (case_id, date_time, usr_id, id) VALUES ('" . $_SESSION["caseidopen"] . "', TIMESTAMPADD(HOUR,-1,CURRENT_TIMESTAMP), '" . $_SESSION["id"] . "', '1')";
if($link->query($create_recent_cas) === true) {
} else {
echo "error executing " . $create_recent_cas . " " . mysqli_error($link);
}
// Delete case records over >10
$sql_recent_case_tot = "SELECT COUNT(*) total FROM `cms_recent_cas` WHERE usr_id=" . $_SESSION["id"];
$result_recent_case_tot = $link->query($sql_recent_case_tot);
if ($result_recent_case_tot->num_rows > 0) {
while($row = $result_recent_case_tot->fetch_assoc())	{
$recent_case_tot = $row["total"];
}
}
$delete_recent_cas = "DELETE FROM `cms_recent_cas` 
WHERE id > 10 
AND " . $recent_case_tot . " > 10 
AND usr_id=" . $_SESSION["id"];
if($link->query($delete_recent_cas) === true) {
//header("location: ../../../app");
//exit;
} else {
echo "error executing " . $delete_recent_cas . " " . mysqli_error($link);
}
// Delete records from cms_recent_cas where case_id is the same as the current open case and the id is greater than 1.
// This ensures that there are not duplicate records of the same case in the cms_recent_cas listing.
$delete_same_cas = "DELETE FROM `cms_recent_cas` WHERE id > 1 AND case_id='" . $_SESSION["caseidopen"] . "' AND usr_id =" . $_SESSION["id"];
if($link->query($delete_same_cas) === true) {
//header("location: ../../../app");
//exit;
} else {
echo "error executing " . $delete_same_cas . " " . mysqli_error($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $_SESSION["businessname"]; ?> | Case <?php if (isset($_SESSION["caseidopen"])) { echo $_SESSION["caseidopen"]; }else{echo "not found."; } ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="../../pace.js"></script>
  <link rel="stylesheet" href="https://legal.dougbros.co.uk/pace.css">
  <script src="../../accordion.js"></script>
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
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color: #f5f5f5;}
div.recent_cases_hide { display:none; }
.wbrow {
	display: flex;
}
.wbcol {
	flex:50%;
	padding: 5px;
	background-color: #ebebeb;
	border-radius: 1rem;
	margin: 5px;
	box-shadow: 1px 1px 2px grey, -1px -1px 2px grey;
}
div.wbcol > h2 {
	padding-left: 10px;
	}
input[readonly].form-control-plaintext{
  background-color: #d9d9d9;
  border-radius: 1.5rem;
  border: 0px;
  padding: 12px 20px;
  font-size: 1em;
  width: 90%;
  border-radius: 1.5rem;
}
.col-form-label {
	padding: 12px; 20px;
}
.address-text-area {
	resize: none;
	box-sizing: border-box;
	border: 0px;
	width: auto;
	height: auto;
	padding: 12px 20px;
	background-color: #d9d9d9;
	border-radius: 1.5rem;
}
.case-data-accordion {
  background-color: #C0C0C0;
  border-radius: 1.5rem;
  color: #000000;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 20px;
  transition: 0.4s;
  margin-bottom: 5px;
  margin-top: 5px;
}
.active, .case-data-accordion:hover {
  background-color: #ccc; 
}
.case-data-accordion-panel {
  padding-right: 18px;
  padding-top: 18px;
  padding-bottom: 18px;
  display: none;
  background-color: #e6e6e6;
  overflow: hidden;
  border-radius: 1.5rem;
}
.form-group.row {
	margin-left: 0px;
	margin-bottom: 0px;
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
     	echo "<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">Actions <span class=\"caret\"></span></a>
        	 <ul class=\"dropdown-menu\">
         		 <li><a href=\"../new\"><span class=\"glyphicon glyphicon-pencil\"></span>&nbsp;&nbsp;New case</a></li>
         		 <li><a href=\"/app\"><span class=\"glyphicon glyphicon-folder-open\"></span>&nbsp;&nbsp;Open case</a></li>
       		 </ul>
     	 </li>";
		}?>
      <li><a href="#"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;Help</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    <li class="active"><a onclick="closeCaseAreYouSure()"><span class="glyphicon glyphicon-remove-circle"></span>&nbsp;Close</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-th"></span> Roles</a>
      <ul class="dropdown-menu">
        <?php $sql = "SELECT DISTINCT role_nar FROM `user_roles` A LEFT JOIN `roles` B ON A.role_cd=B.role_cd WHERE end_date >= CURRENT_DATE AND usr_id =" . $_SESSION['id'];
$result = $link->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<li><a>" . $row["role_nar"] . "</a></li>";
    }
} else {
   echo "<li><a>Err fetching roles</a></li>";
} ?>
      </ul>
      </li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION["username"]); ?></a>
      <ul class="dropdown-menu">
        <li><a><?php $email = $_SESSION['email']; echo $email; ?></a></li>
        <li><a href="../admin/reset">Change password</a></li>
        <li><a href="../admin/reset/email.php">Change email</a></li>
      </ul>
      </li>
      <li><a href="../admin/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<div class="container">
<div class="wbrow">
<div class="wbcol">
<?php 
function case_Data() {
global $case_id, $case_data_sql, $case_data_result, $link, $case_data_id, $case_data_status, $case_data_location, $case_data_created_date, $case_data_closed_date, $case_data_opened_user, $case_data_updated_user, $sql_error, $sql_error_detail;
$case_data_sql = "SELECT case_id, d.code_nar case_stat, CASE WHEN case_loc IS NULL THEN 'Unallocated' ELSE case_loc END AS case_loc, a.created_date, case when a.closed_date is null then 'Case open' else a.closed_date end as closed_date, b.username created_usr, c.username last_updated_usr 
FROM `case` a 
LEFT JOIN users b ON a.created_usr=b.id 
LEFT JOIN users c ON a.last_updated_usr=c.id 
LEFT JOIN codes d ON a.case_stat=d.code_id 
AND d.code_type='CSTAT' 
WHERE a.case_id=" . $case_id;
$case_data_result = $link->query($case_data_sql);
if ($case_data_result->num_rows > 0) {
	while($case_data_row = $case_data_result->fetch_assoc()) {
		$case_data_id = $case_data_row["case_id"];
		$case_data_status = $case_data_row["case_stat"];
		$case_data_location = $case_data_row["case_loc"];
		$case_data_created_date = $case_data_row["created_date"];
		$case_data_closed_date = $case_data_row["closed_date"];
		$case_data_opened_user = $case_data_row["created_usr"];
		$case_data_updated_user = $case_data_row["last_updated_usr"];
	}
} else {
		$sql_error = "Could not retrieve case data.";
		$sql_error_detail = mysqli_error($link);
}
}
function customer_Data() {
global $case_id, $link, $i_dft_add, $i_dft_asoc, $cust_data_sql, $cust_data_result, $cust_data_row, $cust_data_name, $cust_data_dob, $cust_data_adtyp, $cust_data_adl1, $cust_data_adl2, $cust_data_adl3, $cust_data_adl4, $cust_data_adl5, $cust_data_postcode, $cust_data_mob, $cust_data_tel;
$cust_data_sql = "SELECT A.bp_cd, CONCAT(`title`,' ',`nam`,' ',`nam2`) as name, A.dateofbirth, A.email, D.code_nar as adtyp, add_li_1, add_li_2, IFNULL(add_li_3,0) add_li_3, IFNULL(add_li_4,0) add_li_4, IFNULL(add_li_5,0) add_li_5, postcode, mobile, IFNULL(telephone,0) telephone
FROM `add_bp` `A` 
LEFT JOIN `case_ass_bp` `B` ON A.bp_cd=B.bp_cd AND B.assoc_type='" . $i_dft_asoc . "'
LEFT JOIN `addresses` `C` ON B.bp_cd=C.bp_cd AND C.adtyp='" . $i_dft_add . "'
LEFT JOIN `codes` `D` ON C.adtyp=D.code_id AND D.code_type='ADTYP' AND D.not_in_use!=1
WHERE B.case_id=" . $case_id;
$cust_data_result = $link->query($cust_data_sql);
if ($cust_data_result->num_rows > 0) {
	while($cust_data_row = $cust_data_result->fetch_assoc())	{
	$cust_data_name = $cust_data_row["name"];
	$cust_data_dob = $cust_data_row["dateofbirth"];
	$cust_data_adtyp = $cust_data_row["adtyp"];
	$cust_data_adl1 = $cust_data_row["add_li_1"];
	$cust_data_adl2 = $cust_data_row["add_li_2"];
	$cust_data_adl3 = $cust_data_row["add_li_3"];
	$cust_data_adl4 = $cust_data_row["add_li_4"];
	$cust_data_adl5 = $cust_data_row["add_li_5"];
	$cust_data_postcode = $cust_data_row["postcode"];
	$cust_data_mob = $cust_data_row["mobile"];
	$cust_data_tel = $cust_data_row["telephone"];
	}
} else {
		$sql_error = "Could not retrieve customer data.";
		$sql_error_detail = mysqli_error($link);
}
}
?>
  <h2>Case <?php case_Data(); if (isset($_SESSION["caseidopen"])) { echo $_SESSION["caseidopen"]; } else { echo "not found."; } echo "<br>" . $sql_error; ?></h2>
  <?php if (isset($_SESSION["caseidopen"])) { 
	echo "<div class=\"recent_cases_hide\">";
	$case_error = 0;
	} else {
	echo "<div class=\"\">"; 
	$case_error = 1;
   } ?>
  <h3>Recent cases</h3>
  <table><tr><th>Case ID</th><th>Customer</th><th>Last worked</th></tr>
  <?php $sql = "SELECT DISTINCT a.case_id, case when c.salut is null then 'Cannot get customer data' else c.salut end as salut, a.date_time FROM cms_recent_cas a LEFT JOIN case_ass_bp b ON a.case_id=b.case_id AND b.assoc_type='CUST' LEFT JOIN add_bp c ON b.bp_cd=c.bp_cd WHERE a.usr_id=" . $_SESSION['id'] . " ORDER BY id";
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
  <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row customerdata\">";
    }
    ?>
    <label for="customerName" class="col-sm-3 col-form-label">Name:</label>
    <div class="col-sm-9">
<?php
customer_Data();
  if(isset($cust_data_name)) {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerName\" value=\"" . $cust_data_name . "\" disabled>";
  } else {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerName\" value=\"Could not get customer name\" disabled>";
  }
  ?>
  </div>
</div>
  <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row customerdata\">";
    }
    ?>
    <label for="customerAddress" class="col-sm-3 col-form-label"><? echo $cust_data_adtyp; ?> address:</label>
    <div class="col-sm-9">
  <?php
  if(isset($cust_data_name)) {
  echo "<textarea readonly class=\"form-control-plaintext address-text-area\" id=\"customerAddress\" rows=\"6\" cols=\"25\" disabled>" . $cust_data_adl1 . "&#x000d;";
  echo $cust_data_adl2;
  if($cust_data_adl3 != '0') { echo "&#x000d;" . $cust_data_adl3; }
  if($cust_data_adl4 != '0') { echo "&#x000d;" . $cust_data_adl4; }
  if($cust_data_adl5 != '0') { echo "&#x000d;" . $cust_data_adl5; }
  echo "&#x000d;" . $cust_data_postcode;
  echo "</textarea>";
  } else {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerAddress\" value=\"Could not get customer address\" disabled>";
  }
  ?>
  </div>
  </div>
    <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row customerdata\">";
    }
    ?>
    <label for="customerDOB" class="col-sm-3 col-form-label">Date of Birth:</label>
    <div class="col-sm-9">
<?php
customer_Data();
  if(isset($cust_data_dob)) {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerDOB\" value=\"" . $cust_data_dob . "\" disabled>";
  } else {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerDOB\" value=\"Could not get customer DOB\" disabled>";
  }
  ?>
  </div>
</div>
    <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row customerdata\">";
    }
    ?>
    <label for="customerPhone" class="col-sm-3 col-form-label">Phone number:</label>
    <div class="col-sm-9">
<?php
customer_Data();
  if(isset($cust_data_tel)) {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerPhone\" value=\"" . $cust_data_tel . "\" disabled>";
  } else {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerPhone\" value=\"Could not get customer telephone\" disabled>";
  }
  ?>
  </div>
</div>
    <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row customerdata\">";
    }
    ?>
    <label for="customerMob" class="col-sm-3 col-form-label">Mobile number:</label>
    <div class="col-sm-9">
<?php
customer_Data();
  if(isset($cust_data_mob)) {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerMob\" value=\"" . $cust_data_mob . "\" disabled>";
  } else {
  echo "<input type\"text\" readonly class=\"form-control-plaintext\" id=\"customerMob\" value=\"Could not get customer mobile\" disabled>";
  }
  ?>
  </div>
</div>
  <button class="case-data-accordion">Case Data</button>
  <div class="case-data-accordion-panel">
  <form>
  <?php 
      if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
   <label for="caseStatus" class="col-sm-3 col-form-label">Case status:</label>
    <div class="col-sm-9">
    <?php
    if(isset($case_data_status)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseStatus\" value=\"" . $case_data_status . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseStatus\" value=\"Could not get status\" disabled>";
   } 
     ?>
  </div>
  </div>
  <?php
    if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
    <label for="caseLocation" class="col-sm-3 col-form-label">Case location:</label>
    <div class="col-sm-9">
        <?php 
    if(isset($case_data_location)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseLocation\" value=\"" . $case_data_location . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseLocation\" value=\"Could not get location\" disabled>";
   } 
     ?>
    </div>
</div>
 <?php
    if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
    <label for="caseCreated" class="col-sm-3 col-form-label">Case created:</label>
    <div class="col-sm-9">
        <?php 

    if(isset($case_data_created_date)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseCreated\" value=\"" . $case_data_created_date . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseCreated\" value=\"Could not get created date\" disabled>";
   } 
     ?>
    </div>
	</div>
	<?php
	    if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
    <label for="caseClosed" class="col-sm-3 col-form-label">Case closed:</label>
    <div class="col-sm-9">
        <?php 
    if(isset($case_data_closed_date)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseClosed\" value=\"" . $case_data_closed_date . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseClosed\" value=\"Could not get closure date\" disabled>";
   } 
     ?>
    </div>
</div>
<?php
	    if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
    <label for="caseCreatedBy" class="col-sm-3 col-form-label">Created by:</label>
    <div class="col-sm-9">
        <?php 
    if(isset($case_data_opened_user)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseCreatedBy\" value=\"" . $case_data_opened_user . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"caseCreatedBy\" value=\"Could not get creator of case\" disabled>";
   } 
     ?>
    </div>
</div>
<?php
	    if($case_error === 1) {
		echo "<div class=\"recent_cases_hide\">";
    } else {
		echo "<div class=\"form-group row\">";
    }
  ?>
    <label for="lastUpdatedBy" class="col-sm-3 col-form-label">Last updated by:</label>
    <div class="col-sm-9">
        <?php 
    if(isset($case_data_updated_user)) {
    echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"lastUpdatedBy\" value=\"" . $case_data_updated_user . "\" disabled>";
    } else {
     echo "<input type=\"text\" readonly class=\"form-control-plaintext\" id=\"lastUpdatedBy\" value=\"Could not get last updater of case\" disabled>";
   }
     ?>
    </div>
</div>
  </form>
  </div>
  </div>
  <div class="wbcol">
  <?php
  if(isset($sql_error_detail)) {
  echo "<h2>Error</h2><br><h4>" . $sql_error_detail . ".</br>Please speak to your administrator, quoting the error details above and current URL: " . $CurrentURL . ". </h4>";
  } else 
  {
  }
  ?>
  </div>
<script>
var acc = document.getElementsByClassName("case-data-accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

function closeCaseAreYouSure() {
  var r = confirm("Are you sure you want to close this case?");
  if (r == true) {
    location.replace("../");
  }
}
</script>
</div>
</body>
</html>