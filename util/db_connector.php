<?php
header ( "Content-Type: text/html; charset=utf8" );
function db_connector() {
	$host = "localhost"; // Host name
	$username = "Your User Name"; // Mysql username
	$password = "Your Password"; // Mysql password
	$db_name = "Your Database Name"; // Database name
	$tbl_name = "Your Table Name"; // Table name
	
	$conn = mysqli_connect ( "$host", "$username", "$password", "$db_name" ) or die ( "db_connector_die" );
	;
	mysqli_query ( $conn, 'set names utf8' );
	
	return $conn;
}
?>