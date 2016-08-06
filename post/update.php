<?php
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_post"; // Table name

$member_srl = $_REQUEST ['member_srl'];
$post_srl = $_REQUEST ['post_srl'];
$post_content = $_REQUEST ['post_content'];

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($post_srl == "") {
	error ( "post_srl null" );
	return;
}
if ($post_content == "") {
	error ( "post_content null" );
	return;
}

$sql = "UPDATE $tbl_name SET post_content='$post_content' WHERE post_srl='$post_srl'";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	
	$array = array (
			'stat' => 1,
			'response' => array (
					'post_content' => $post_content 
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
}

mysqli_close ( $conn );
?>