<?php
require 'config.php'; // Assume this file contains the necessary constants or variables

$username = DVWA_DB_USER;
$password = DVWA_DB_PASSWORD;
$database = DVWA_DB_DATABASE;

mssql_connect(DVWA_DB_HOST, $username, $password);
mssql_select_db($database);

$query ="SELECT * FROM users";
$result =mssql_query($query);
while ( $record = mssql_fetch_array($result) ) {
	echo $record["first_name"] .", ". $record["password"] ."<br />";
}
?>
