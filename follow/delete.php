<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$tb_name = "s_follow_history";

$member_srl = $_REQUEST ['member_srl'];
$follow_member_srl = $_REQUEST ['follow_member_srl'];

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($follow_member_srl == "") {
	error ( "follow_member_srl null" );
	return;
}

$sql = "SELECT * FROM $tb_name WHERE member_srl='$member_srl' and follow_member_srl='$follow_member_srl'";
$result = mysqli_query ( $conn, $sql );
// Mysql_num_row is counting table row
$count = mysqli_num_rows ( $result );

if ($count === 0) {
	error ( "Follow Error!" );
	return;
} else {
	$sql = "DELETE FROM $tb_name WHERE member_srl = '$member_srl' and follow_member_srl = '$follow_member_srl'";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		
		$array = array (
				'stat' => 1,
				'follow_member' => get_member ( $conn, $follow_member_srl ) 
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
	}
}
?>