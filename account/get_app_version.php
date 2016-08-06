<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_app_version";

$device_os = $_GET ['device_os'];

if ($device_os == "") {
	error ( "device_os null" );
	return;
}

$sql = "SELECT version FROM $tbl_name WHERE os='$device_os' ORDER BY update_date desc";
$result = mysqli_query ( $conn, $sql );
// Mysql_num_row is counting table row
$count = mysqli_num_rows ( $result );

if ($count > 0) {
	$row = mysqli_fetch_row ( $result );
	$array = array (
			'stat' => 1,
			'response' => array (
					'version' => $row [0] 
			) 
	);
	
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
} else {
	error ( "error" );
}
?>