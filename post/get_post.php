<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/counter.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!

$tbl_name = "s_post"; // Table name

$conn = db_connector ();

$post_srl = $_GET ['post_srl'];

if ($post_srl == "") {
	error ( "post_srl null" );
	return;
}

$sql = "SELECT * FROM $tbl_name Where post_srl = '$post_srl' "; // Only Follow!

$result = mysqli_query ( $conn, $sql );
$row = mysqli_fetch_row ( $result );

$array = array (
		'stat' => 1,
		'post' => get_post ( $conn, $post_srl ) 
);

header ( 'Content-type: application/json' );
echo json_encode ( $array );
?>