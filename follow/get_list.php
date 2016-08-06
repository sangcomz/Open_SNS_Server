<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$tb_name = "s_follow_history";

$member_srl = $_GET ['member_srl'];
$mode = $_GET ['mode'];
$page = $_GET ['page'];
$posts_num = 30;

if ($page == "") {
	$page = 1;
}

$start_post = $posts_num * $page - $posts_num;
$end_post = $posts_num * $page;

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($mode == "") {
	error ( "mode null" );
	return;
}

// ////////////////////////get page count
if (! strcmp ( $mode, "following" )) {
	$sql = "SELECT count(*) FROM $tb_name where member_srl = '$member_srl' ORDER BY follow_date desc";
} else if (! strcmp ( $mode, "follower" )) {
	$sql = "SELECT count(*) FROM $tb_name where follow_member_srl = '$member_srl' ORDER BY follow_date desc";
}
$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
$row = mysqli_fetch_row ( $result );

$total_post_count = $row [0];

$total_page = ceil ( $total_post_count / $posts_num );
// //////////////////////////

if (! strcmp ( $mode, "following" )) {
	$sql = "SELECT * FROM $tb_name where member_srl = '$member_srl' ORDER BY follow_date desc LIMIT $start_post, $end_post";
	// $sql="SELECT * FROM $tb_name where member_srl = '$member_srl' and post_srl = '$post_srl' ORDER BY follow_date desc LIMIT $start_post, $end_post";
} else if (! strcmp ( $mode, "follower" )) {
	$sql = "SELECT * FROM $tb_name where follow_member_srl = '$member_srl' ORDER BY follow_date desc LIMIT $start_post, $end_post";
	// $sql="SELECT * FROM $tb_name where sns_id = '$sns_id' and post_srl = '$post_srl' ORDER BY follow_date desc LIMIT $start_post, $end_post";
} else {
	error ( "mode is incorrect" );
	return;
}

$result = mysqli_query ( $conn, $sql );
$count = mysqli_num_rows ( $result );

if ($count == 0) {
	$follow_array = array ();
} else {
	for($i = 0; $i < $count; $i ++) {
		$row = mysqli_fetch_row ( $result );
		if (! strcmp ( $mode, "following" )) {
			$follow_array [] = array (
					'member_srl' => $row [1],
					'member_name' => get_member_name ( $conn, $row [1] ),
					'member_profile' => get_member_profile ( $conn, $row [1] ),
					'follow_yn' => get_is_follow ( $conn, $member_srl, $row [1] ) 
			);
		} else if (! strcmp ( $mode, "follower" )) {
			$follow_array [] = array (
					'member_srl' => $row [3],
					'member_name' => get_member_name ( $conn, $row [3] ),
					'member_profile' => get_member_profile ( $conn, $row [3] ),
					'follow_yn' => get_is_follow ( $conn, $member_srl, $row [3] ) 
			);
		}
	}
}

$array = array (
		'stat' => 1,
		'follow_members' => $follow_array,
		'page' => array (
				'current_page' => $page,
				'total_page' => $total_page 
		) 
);

header ( 'Content-type: application/json' );
echo json_encode ( $array );

?>