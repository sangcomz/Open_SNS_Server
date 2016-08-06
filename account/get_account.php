<?php
error_reporting ( E_ALL );
ini_set ( "display_errors", 1 );

header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/counter.php"; // root!!

$conn = db_connector ();

$tbl_name = "s_member";

$member_srl = $_GET ['member_srl'];
$profile_member_srl = $_GET ['profile_member_srl'];
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}

$sql = "SELECT * FROM $tbl_name WHERE member_srl='$profile_member_srl'";
$result = mysqli_query ( $conn, $sql );
// Mysql_num_row is counting table row
$count = mysqli_num_rows ( $result );

if ($count > 0) {
	$row = mysqli_fetch_row ( $result );
	$array = array (
			'stat' => 1,
			'members' => array (
					'member_srl' => $row [0],
					'member_name' => $row [1],
					'member_profile' => $row [3],
					'member_profile_bg' => $row [4],
					'follow_yn' => get_is_follow ( $conn, $member_srl, $row [0] ),
					'member_post_count' => get_post_count ( $conn, $row [0] ),
					'member_follower_count' => get_follower_count ( $conn, $row [0] ),
					'member_following_count' => get_following_count ( $conn, $row [0] ),
					'is_my_profile' => is_mine ( $member_srl, $row [0] ) 
			) 
	);
	
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
} else {
	error ( "error" );
}
?>