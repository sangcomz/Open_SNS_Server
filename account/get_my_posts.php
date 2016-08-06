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

$post_array = array ();
$member_srl = $_GET ['member_srl'];
$page = $_GET ['page'];
$posts_num = 6;

if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($page == "") {
	$page = 1;
}

$start_post = $posts_num * $page - $posts_num;
$end_post = $posts_num * $page;

$members = get_following_member_srls ( $conn, $member_srl );
$matches = implode ( ',', $members );
// print_r($matches);

$sql = "SELECT count(*) FROM $tbl_name Where member_srl ='$member_srl' ORDER BY post_date desc"; // Only Follow!
$result = mysqli_query ( $conn, $sql ) or die ( "get_error" );
$row = mysqli_fetch_row ( $result );

$total_post_count = $row [0];

$total_page = ceil ( $total_post_count / $posts_num );

// echo "total_page :::: ", $total_page,"\n";
// echo "count :::: ", $row[0],"\n";

// $sql="SELECT * FROM $tbl_name ORDER BY post_date desc LIMIT $start_post, $end_post"; //Paging
// echo $sql . "\n";
$sql = "SELECT * FROM $tbl_name Where member_srl ='$member_srl' ORDER BY post_date desc LIMIT $start_post, $end_post"; // Only Follow!
                                                                                                                       // $sql="SELECT * FROM $tbl_name ORDER BY post_date desc";//All

$result = mysqli_query ( $conn, $sql );
$count = mysqli_num_rows ( $result );
// echo "count :::: ", $count,"\n";
for($i = 0; $i < $count; $i ++) {
	$row = mysqli_fetch_row ( $result );
	// echo get_post_is_puzzled($conn, $member_srl, $row[1]);
	$post_array [] = array (
			'post_srl' => $row [0],
			'member_srl' => $row [4],
			'member_name' => get_member_name ( $conn, $row [4] ),
			'member_profile' => get_member_profile ( $conn, $row [4] ),
			'post_image' => $row [1],
			'post_content' => $row [2],
			'post_date' => $row [3],
			'post_comment_count' => get_comment_count ( $conn, $row [0] ) 
	);
}

$array = array (
		'stat' => 1,
		'posts' => $post_array,
		'page' => array (
				'current_page' => $page,
				'total_page' => $total_page 
		) 
);

header ( 'Content-type: application/json' );
echo json_encode ( $array );
?>