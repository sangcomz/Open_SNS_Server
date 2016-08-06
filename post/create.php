<?php
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/error_controller.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/db_connector.php"; // root!!
require_once $_SERVER [DOCUMENT_ROOT] . "/open_sns/util/get_db_info.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_post"; // Table name

$post_srl = str_replace ( ".", "", (microtime ( get_as_float )) );
$member_srl = $_REQUEST ['member_srl'];
$post_content = $_REQUEST ['post_content'];
// echo "post_piece_count :::: ", $post_piece_count,"\n";

// check params
if ($member_srl == "") {
	error ( "member_srl null" );
	return;
}
if ($post_content == "") {
	error ( "post_content null" );
	return;
}

$file_path = $_SERVER ['DOCUMENT_ROOT'];
$file_path .= '/open_sns/post/images/';
$post_url = 'http://sangcomz.xyz/open_sns/post/images/';
$post_url .= $post_srl . '.png';
$file_path .= $post_srl . '.png';

// echo $file_path . "\n";
if (move_uploaded_file ( $_FILES ['post_image'] ['tmp_name'], $file_path )) {
	// echo "success";
	$post_image = $post_url;
} else {
	// echo "fail";
}

$sql = "INSERT INTO $tbl_name (post_srl, member_srl, post_image, post_content, post_date)
		VALUES ('$post_srl','$member_srl', '$post_image', '$post_content', UNIX_TIMESTAMP())";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	// echo "New record created successfully";
	
	$array = array (
			'stat' => 1,
			'post' => get_post ( $conn, $post_srl ) 
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