<?php
header ( "Content-Type: text/html; charset=utf8" );

// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/gcm/gcm.php"; // root!!

$conn = db_connector ();

$tbl_name = "s_comment"; // Table name
                         
// $conn = mysqli_connect("$host", "$username", "$password", "$db_name");
                         // mysqli_query($conn,'set names utf8');

$comment_srl = str_replace ( ".", "", (microtime ( get_as_float )) );
$member_srl = $_REQUEST ['member_srl'];
$post_srl = $_REQUEST ['post_srl'];
$comment_content = $_REQUEST ['comment_content'];

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($post_srl == "") {
	error ( "post_srl null" );
	return;
}
if ($comment_content == "") {
	error ( "comment_content null" );
	return;
}

$sql = "INSERT INTO $tbl_name (comment_srl, member_srl, post_srl, comment_content, comment_date)
	VALUES ('$comment_srl','$member_srl', '$post_srl', '$comment_content', UNIX_TIMESTAMP())";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	// echo "New record created successfully";
	
	// $comment_array[] = array(
	// 'comment_srl' => $row[0], 'member_srl' => $row[1],'member_name' => get_member_name($conn, $row[1]),
	// 'member_profile' => get_member_profile($conn, $row[1]),
	// 'comment_content' => $row[2], 'comment_date' => $row[3],
	// 'post_srl' => $row[4], 'is_my_comment' =>is_mine($member_srl, $row[1])
	
	$array = array (
			'stat' => 1,
			'comments' => array (
					'comment_srl' => $comment_srl,
					'member_srl' => $member_srl,
					'member_name' => get_member_name ( $conn, $member_srl ),
					'member_profile' => get_member_profile ( $conn, $member_srl ),
					'comment_content' => $comment_content,
					'comment_date' => $row [3],
					'post_srl' => $post_srl,
					'is_my_comment' => 'Y' 
			) 
	);
	
	header ( 'Content-type: application/json' );
	echo json_encode ( $array );
	
	$post_writer_member_srl = get_post_writer_member_srl ( $conn, $post_srl );
	
	if (strcmp ( $post_writer_member_srl, $member_srl )) {
		commentGCM ( $conn, $post_writer_member_srl, $member_srl, $post_srl, $comment_srl, $comment_content );
	}
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