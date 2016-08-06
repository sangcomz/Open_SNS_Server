<?php
header ( "Content-Type: text/html; charset=utf8" );

require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_phone_info"; // Table name

$member_srl = $_REQUEST ['member_srl'];

// $sql="SELECT * FROM $tbl_name WHERE sns_id='$sns_id'";

// $result=mysqli_query($conn, $sql);

// Mysql_num_row is counting table row

// $count=mysqli_num_rows($result);

// $sql = UPDATE $tbl_name SET sns_name=$sns_name, sns_profile=$sns_profile WHERE sns_id='$sns_id'";

// $sql = "UPDATE $tbl_name SET sns_name='$sns_name', sns_profile='$sns_profile' WHERE sns_id='$sns_id'";
$sql = "UPDATE $tbl_name SET member_os='', member_device_token='' , member_login_date='' WHERE member_srl='$member_srl'";

if (mysqli_query ( $conn, $sql ) === TRUE) {
	// echo "update successfully";
	$array = array (
			'stat' => 1,
			'response' => array (
					'member_srl' => $member_srl 
			) 
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