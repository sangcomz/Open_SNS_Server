<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$member_srl = $_GET ['member_srl'];

if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}

$setting_push_on_off = get_setting_push_on_off ( $conn, $member_srl );
$setting_searchable = get_setting_searchable ( $conn, $member_srl );

if ($setting_push_on_off != 'err' && $setting_searchable != 'err') {
	$array = array (
			'stat' => 1,
			'response' => array (
					'member_srl' => $member_srl,
					'setting_push_on_off' => $setting_push_on_off,
					'setting_push_on_off' => $setting_searchable 
			) 
	);
	
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
} else {
	$array = array (
			'stat' => 0,
			'response' => array (
					'Error' => 'error something' 
			) 
	);
	
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
	// echo "Error: " . $sql . "<br>" . $conn->error;
}

?>
