<?php
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/counter.php"; // root!!
function get_member_name($conn, $member_srl) {
	$tb_name = "s_member";
	$sql = "SELECT * FROM $tb_name WHERE member_srl = '$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	return $row [1];
}
function get_member_profile($conn, $member_srl) {
	$tb_name = "s_member";
	$sql = "SELECT * FROM $tb_name WHERE member_srl = '$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	return $row [3];
}
function get_member_device_token($conn, $member_srl) {
	$tb_name = "s_phone_info";
	$sql = "SELECT * FROM $tb_name WHERE member_srl = '$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	return $row [1];
}
function get_post_writer_member_srl($conn, $post_srl) {
	$tb_name = "s_post";
	$sql = "SELECT member_srl FROM $tb_name WHERE post_srl = '$post_srl'";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	return $row [0];
}
function get_member($conn, $member_srl) {
	$tb_name = "s_member";
	$sql = "SELECT * FROM $tb_name WHERE member_srl = '$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	
	$array = array (
			'member_srl' => $row [0],
			'member_name' => $row [1],
			'member_profile' => $row [3],
			'member_profile_bg' => $row [4],
			'follow_yn' => get_is_follow ( $conn, $member_srl, $row [0] ),
			'member_post_count' => get_post_count ( $conn, $row [0] ),
			'member_follower_count' => get_follower_count ( $conn, $row [0] ),
			'member_following_count' => get_following_count ( $conn, $row [0] ),
			'is_my_profile' => is_mine ( $member_srl, $row [0] ) 
	);
	
	return $array;
}
function get_post($conn, $post_srl) {
	$tb_name = "s_post";
	$sql = "SELECT * FROM $tb_name Where post_srl = '$post_srl' ";
	$result = mysqli_query ( $conn, $sql );
	$row = mysqli_fetch_row ( $result );
	
	$array = array (
			'post_srl' => $row [0],
			'member_srl' => $row [4],
			'member_name' => get_member_name ( $conn, $row [4] ),
			'member_profile' => get_member_profile ( $conn, $row [4] ),
			'post_image' => $row [1],
			'post_content' => $row [2],
			'post_date' => $row [3],
			'post_comment_count' => get_comment_count ( $conn, $row [0] ) 
	);
	
	return $array;
}
function get_following_member_srls($conn, $member_srl) {
	$tb_name = "s_follow_history";
	$sql = "SELECT follow_member_srl FROM $tb_name where member_srl = '$member_srl' ORDER BY follow_date desc"; // follow_sns_id ���ϱ�.
	$result = mysqli_query ( $conn, $sql );
	$count = mysqli_num_rows ( $result );
	$members [] = "'" . $member_srl . "'";
	// $members[] = null;
	for($i = 0; $i < $count; $i ++) {
		$row = mysqli_fetch_row ( $result );
		// echo $row[0] . "\n";
		// $mebers[] = $row[0];
		array_push ( $members, "'" . $row [0] . "'" );
		// $members[] = array('$row[0]');
	}
	
	return $members;
}

// puzzled == request sns id, snd id == post sns id
function get_is_follow($conn, $member_srl, $follow_member_srl) {
	$tb_name = "s_follow_history";
	$sql = "SELECT follow_member_srl FROM $tb_name where member_srl = '$member_srl' and follow_member_srl = '$follow_member_srl'"; // follow_sns_id ���ϱ�.
	$result = mysqli_query ( $conn, $sql );
	$count = mysqli_num_rows ( $result );
	if ($count > 0) {
		return "Y";
	} else {
		return "N";
	}
}
function get_setting_push_on_off($conn, $member_srl) {
	$tb_name = "s_phone_setting";
	$sql = "SELECT setting_push_on_off FROM $tb_name WHERE member_srl='$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$count = mysqli_num_rows ( $result );
	if ($count > 0) {
		$row = mysqli_fetch_row ( $result );
		return $row [0];
	} else {
		return "err";
	}
}
function get_setting_searchable($conn, $member_srl) {
	$tb_name = "s_phone_setting";
	$sql = "SELECT setting_searchable FROM $tb_name WHERE member_srl='$member_srl'";
	$result = mysqli_query ( $conn, $sql );
	$count = mysqli_num_rows ( $result );
	if ($count > 0) {
		$row = mysqli_fetch_row ( $result );
		return $row [0];
	} else {
		return "err";
	}
}
function get_no_searchable_member_srls($conn, $member_srl) {
	$tb_name = "s_phone_setting";
	$sql = "SELECT member_srl FROM $tb_name where setting_searchable ='N'"; // follow_sns_id ���ϱ�.
	$result = mysqli_query ( $conn, $sql );
	$count = mysqli_num_rows ( $result );
	$members [] = "'" . $member_srl . "'";
	// $members[] = null;
	for($i = 0; $i < $count; $i ++) {
		$row = mysqli_fetch_row ( $result );
		// echo $row[0] . "\n";
		// $mebers[] = $row[0];
		array_push ( $members, "'" . $row [0] . "'" );
		// $members[] = array('$row[0]');
	}
	
	return $members;
}
function is_mine($my_member_srl, $the_other_member_srl) {
	if (! strcmp ( $my_member_srl, $the_other_member_srl )) {
		return "Y";
	} else {
		return "N";
	}
}

?>