<?php
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_member"; // Table name

$member_srl = $_REQUEST ['member_srl'];
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
$photo_srl = str_replace ( ".", "", (microtime ( get_as_float )) );
$file_path = $_SERVER ['DOCUMENT_ROOT'];
$file_path .= '/open_sns/account/images/bg/';
$ori_url = 'http://sangcomz.xyz/open_sns/account/images/bg/';
$ori_url .= $photo_srl . '_' . $member_srl . '_bg.png';
$file_path .= $photo_srl . '_' . $member_srl . '_bg.png';

if (move_uploaded_file ( $_FILES ['member_profile_bg'] ['tmp_name'], $file_path )) {
	$member_profile_bg_url = $ori_url;
} else {
	error ( "do not save image" );
	return;
}

$sql = "SELECT * FROM $tbl_name WHERE member_srl='$member_srl'";

$result = mysqli_query ( $conn, $sql );

// Mysql_num_row is counting table row

$count = mysqli_num_rows ( $result );

if ($count > 0) {
	
	$row = mysqli_fetch_row ( $result );
	
	$pattern = '/';
	$address = explode ( $pattern, $row [4] );
	$file_name = $address [count ( $address ) - 1];
	$file_path = $_SERVER ['DOCUMENT_ROOT'];
	$file_path .= '/open_sns/account/images/bg/';
	$file_path .= $file_name;
	
	unlink ( $file_path );
	
	$sql = "UPDATE $tbl_name SET member_profile_bg ='$member_profile_bg_url' WHERE member_srl='$member_srl'";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		$array = array (
				'stat' => 1,
				'response' => get_member ( $conn, $member_srl ) 
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
} else {
	error ( "error member_srl" );
	return;
}

mysqli_close ( $conn );
?>