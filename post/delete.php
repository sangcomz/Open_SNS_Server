<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_post"; // Table name
$tb3_name = "s_comment"; // Table name

$member_srl = $_REQUEST ['member_srl'];
$post_srl = $_REQUEST ['post_srl'];

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($post_srl == "") {
	error ( "post_srl null" );
	return;
}

$sql = "SELECT post_piece_count post FROM $tbl_name WHERE post_srl = '$post_srl'";
$result = mysqli_query ( $conn, $sql );
$row = mysqli_fetch_row ( $result );
$post_piece_count = $row [0];
// echo $post_piece_count ."\n";

$sql = "DELETE FROM $tbl_name Where member_srl = '$member_srl' and post_srl = '$post_srl'";
// echo $sql . "\n";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	// echo "New record created successfully";
	$sql = "DELETE FROM $tb3_name Where post_srl = '$post_srl'";
	mysqli_query ( $conn, $sql );
	
	// ori image del
	$file_path = $_SERVER ['DOCUMENT_ROOT'];
	$file_path = $file_path . '/open_sns/post/images/';
	$file_path = $file_path . $post_srl . '.png';
	unlink ( $file_path );
	
	$array = array (
			'stat' => 1,
			'response' => array () 
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

mysqli_close ( $conn );
?>