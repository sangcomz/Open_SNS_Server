<?php
header ( "Content-Type: text/html; charset=utf8" );
function get_comment_count($conn, $post_srl) {
	$tb_name = "s_comment"; // Table name
	$sql = "SELECT count(*) FROM $tb_name WHERE post_srl='$post_srl'";
	
	$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
	$row = mysqli_fetch_row ( $result );

	return $row [0];
}
function get_post_count($conn, $member_srl) {
	$tb_name = "s_post"; // Table name
	$sql = "SELECT count(*) FROM $tb_name WHERE member_srl='$member_srl'";
	
	$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
	$row = mysqli_fetch_row ( $result );
	
	return $row [0];
}
function get_follower_count($conn, $member_srl) {
	$tb_name = "s_follow_history"; // Table name
	$sql = "SELECT count(*) FROM $tb_name WHERE member_srl='$member_srl'";
	
	$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
	$row = mysqli_fetch_row ( $result );
	
	return $row [0];
}
function get_following_count($conn, $member_srl) {
	$tb_name = "s_follow_history"; // Table name
	$sql = "SELECT count(*) FROM $tb_name WHERE follow_member_srl	='$member_srl'";
	
	$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
	$row = mysqli_fetch_row ( $result );
	
	return $row [0];
}

?>