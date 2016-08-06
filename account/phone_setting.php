<?php
header ( "Content-Type: text/html; charset=utf8" );

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/error_controller.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_phone_setting"; // Table name

$member_srl = $_REQUEST ['member_srl'];
$push_on_off = $_REQUEST ['push_on_off'];
$searchable = $_REQUEST ['searchable'];

if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($push_on_off == "") {
	error ( "push_on_off null" );
	return;
}
if ($searchable == "") {
	error ( "searchable null" );
	return;
}

$sql = "SELECT * FROM $tbl_name WHERE member_srl='$member_srl'";

$result = mysqli_query ( $conn, $sql );

// Mysql_num_row is counting table row

$count = mysqli_num_rows ( $result );

if ($count > 0) {
	// $sql = UPDATE $tbl_name SET sns_name=$sns_name, sns_profile=$sns_profile WHERE sns_id='$sns_id'";
	
	$sql = "UPDATE $tbl_name SET setting_push_on_off='$push_on_off', setting_searchable='$searchable' WHERE member_srl='$member_srl'";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		// echo "update successfully";
		$array = array (
				'stat' => 1,
				'response' => array (
						'member_srl' => $member_srl,
						'setting_push_on_off' => $push_on_off,
						'setting_searchable' => $searchable 
				) 
		);
		
		header ( 'Content-type: application/json' );
		echo json_encode ( $array );
	} else {
		$array = array (
				'stat' => 0,
				'response' => array (
						'Error' => $conn->error 
				) 
		);
		
		header ( 'Content-type: application/json' );
		echo json_encode ( $array );
		// echo "Error: " . $sql . "<br>" . $conn->error;
	}
} else {
	if ($searchable == "") {
		error ( "member_srl is not found" );
		return;
	}
}

mysqli_close ( $conn );
?>