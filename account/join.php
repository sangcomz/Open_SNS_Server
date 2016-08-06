<?php
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_member"; // Table name
$tb2_name = "s_phone_setting"; // Table name

$member_srl = str_replace ( ".", "", (microtime ( get_as_float )) );
$member_name = $_REQUEST ['member_name'];
$member_pwd = $_REQUEST ['member_pwd'];
$member_profile = "";

if ($member_name == "") {
	error ( "member_name null" );
	return;
}
if ($member_pwd == "") {
	error ( "member_pwd null" );
	return;
}

if ($_FILES ['member_profile'] ['size'] == 0 && $_FILES ['member_profile'] ['error'] == 0) {
} else {
	// cover_image is empty (and not an error)
	$file_path = $_SERVER ['DOCUMENT_ROOT'];
	$file_path .= '/open_sns/account/images/';
	$ori_url = 'http://sangcomz.xyz/open_sns/account/images/';
	$ori_url .= str_replace ( ".", "", (microtime ( get_as_float )) ) . '_' . $member_srl . '.png';
	$file_path .= str_replace ( ".", "", (microtime ( get_as_float )) ) . '_' . $member_srl . '.png';
	
	// echo $file_path . "\n";
	if (move_uploaded_file ( $_FILES ['member_profile'] ['tmp_name'], $file_path )) {
		// echo "success";
		$member_profile = $ori_url;
	} else {
		// echo "fail";
	}
}

$sql = "SELECT * FROM $tbl_name WHERE member_name='$member_name'";

$result = mysqli_query ( $conn, $sql );

// Mysql_num_row is counting table row

$count = mysqli_num_rows ( $result );

if ($count > 0) {
	error ( "Duplicate member_name" );
	return;
} else {
	$sql = "INSERT INTO $tbl_name (member_srl, member_name, member_pwd, member_profile)
			VALUES ('$member_srl', '$member_name', '$member_pwd', '$member_profile')";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		// echo "New record created successfully";
		
		$sql = "INSERT INTO $tb2_name (member_srl, setting_push_on_off, setting_searchable)
				VALUES ('$member_srl', 'Y', 'Y')";
		mysqli_query ( $conn, $sql );
		
		$array = array (
				'stat' => 1,
				'response' => array (
						'member_srl' => $member_srl,
						'member_name' => $member_name,
						'member_profile' => $member_profile,
						'member_profile_bg' => "" 
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
}

mysqli_close ( $conn );
?>