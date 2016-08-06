<?php
header ( "Content-Type: text/html; charset=utf8" );
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_member"; // Table name

$member_srl = $_GET ['member_srl'];
$query = $_GET ['query'];
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
if ($query == "") {
	error ( "query null" );
	return;
}

$members = get_no_searchable_member_srls ( $conn, $member_srl );
$matches = implode ( ',', $members );
// print_r("print".$matches);

//////////////////////////get page count
$sql = "SELECT count(*) FROM $tbl_name where member_name like '%$query%' And member_srl Not in($matches) ";
// echo "sql :::: ", $sql,"\n";
$result = mysqli_query ( $conn, $sql ) or die ( "get_search_error" );
$row = mysqli_fetch_row ( $result );

$total_post_count = $row [0];

$total_page = ceil ( $total_post_count / $posts_num );
// //////////////////////////

$sql = "SELECT * FROM $tbl_name where member_name like '%$query%' And member_srl Not in($matches) LIMIT $start_post, $end_post";
// echo $sql . "\n";
$result = mysqli_query ( $conn, $sql );
$count = mysqli_num_rows ( $result );
// echo "count :::: ", $count,"\n";
if ($count == 0) {
	$search_array = array ();
} else {
	for($i = 0; $i < $count; $i ++) {
		$row = mysqli_fetch_row ( $result );
		$search_array [] = array (
				'member_srl' => $row [0],
				'member_name' => $row [1],
				'member_profile' => $row [3],
				'follow_yn' => get_is_follow ( $conn, $member_srl, $row [0] ) 
		);
	}
}

$array = array (
		'stat' => 1,
		'follow_members' => $search_array,
		'page' => array (
				'current_page' => $page,
				'total_page' => $total_page 
		) 
);

header ( 'Content-type: application/json' );
echo json_encode ( $array );

?>
