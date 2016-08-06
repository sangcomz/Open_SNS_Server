<?php

// error_reporting(E_ALL);
// ini_set("display_errors", 1);
header ( "Content-Type: text/html; charset=utf8" );

// function is_mine($my_member_srl, $the_other_member_srl)
// {
// if(!strcmp($my_member_srl, $the_other_member_srl)){
// return "Y";
// }else{
// return "N";
// }
// }

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!

$conn = db_connector ();

$tbl_name = "s_comment"; // Table name

$member_srl = $_GET ['member_srl'];
$post_srl = $_GET ['post_srl'];
$page = $_GET ['page'];
$comments_num = 20;

if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($post_srl == "") {
	error ( "post_srl null" );
	return;
}
if ($page == "") {
	$page = 1;
}

$start_post = $comments_num * $page - $comments_num;
$end_post = $comments_num * $page;

$sql = "SELECT count(*) FROM $tbl_name where post_srl = '$post_srl' ORDER BY comment_date desc";
$result = mysqli_query ( $conn, $sql ) or die ( "get_comment_error" );
$row = mysqli_fetch_row ( $result );

$total_comment_count = $row [0];

$total_page = ceil ( $total_comment_count / $comments_num );

$comment_array = array ();

$sql = "SELECT * FROM $tbl_name where post_srl = '$post_srl' ORDER BY comment_date desc LIMIT $start_post, $end_post";
// echo $sql,"\n";
$result = mysqli_query ( $conn, $sql );
$count = mysqli_num_rows ( $result );
// echo "count :::: ", $count,"\n";
for($i = 0; $i < $count; $i ++) {
	$row = mysqli_fetch_row ( $result );
	$comment_array [] = array (
			'comment_srl' => $row [0],
			'member_srl' => $row [1],
			'member_name' => get_member_name ( $conn, $row [1] ),
			'member_profile' => get_member_profile ( $conn, $row [1] ),
			'comment_content' => $row [2],
			'comment_date' => $row [3],
			'post_srl' => $row [4],
			'is_my_comment' => is_mine ( $member_srl, $row [1] ) 
	);
}

$array = array (
		'stat' => 1,
		'comments' => $comment_array,
		'page' => array (
				'current_page' => $page,
				'total_page' => $total_page,
				'total_comment_count' => $total_comment_count 
		) 
);

header ( 'Content-type: application/json' );
echo json_encode ( $array );

?>