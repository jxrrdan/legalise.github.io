<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'db5000279785.hosting-data.io');
define('DB_USERNAME', 'dbu63513');
define('DB_PASSWORD', 'Legal20!');
define('DB_NAME', 'dbs273099');
/* Attempt to connect to MySQL database */
//$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 $link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
$sql = "SELECT bus_name FROM config";
$result = $link->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $_SESSION["businessname"] = $row["bus_name"];
    }
} else {
   $_SESSION["businessname"] = "Err fetching bus_name";
}
?>