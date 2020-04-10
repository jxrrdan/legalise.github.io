<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
header("location: ../../../login");
exit;
}
require_once('../../../config.php'); 
if(isset($_SESSION["id"])) {
$id = $_SESSION["id"];
if ($stmt = $link->prepare("SELECT username, email, created_date FROM users WHERE id = ?")) {
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->store_result();
}
}
// Get username to store create note.
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
$create_recent_cas = "INSERT INTO `cms_recent_cas` (case_id, date_time, usr_id, id) VALUES ('" . $i_next_case_id . "', TIMESTAMPADD(HOUR,-1,CURRENT_TIMESTAMP), '" . $_SESSION["id"] . "', '1')";
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
$delete_same_cas = "DELETE FROM `cms_recent_cas` WHERE id > 1 AND case_id='" . $i_next_case_id . "' AND usr_id =" . $_SESSION["id"];
if($link->query($delete_same_cas) === true) {
header("location: ../../../app");
exit;
} else {
echo "error executing " . $delete_same_cas . " " . mysqli_error($link);
}
?>