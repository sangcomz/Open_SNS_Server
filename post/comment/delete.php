<?php
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_comment"; // Table name

$member_srl = $_REQUEST ['member_srl'];
$comment_srl = $_REQUEST ['comment_srl'];

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($comment_srl == "") {
	error ( "comment_srl null" );
	return;
}

$sql = "DELETE FROM $tbl_name WHERE member_srl = '$member_srl' and comment_srl = '$comment_srl'";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	// echo "New record created successfully";
	
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