<?php
header ( "Content-Type: text/html; charset=utf8" );


require_once $_SERVER[DOCUMENT_ROOT]."/open_sns/util/db_connector.php"; // root!!
$conn = db_connector ();

$tbl_name = "s_phone_info"; // Table name

$member_srl = $_REQUEST ['member_srl'];
$member_device_token = $_REQUEST ['member_device_token'];

$sql = "SELECT * FROM $tbl_name WHERE member_srl='$member_srl'";

$result = mysqli_query ( $conn, $sql );

$count = mysqli_num_rows ( $result );

if ($count > 0) {
	// $sql = UPDATE $tbl_name SET sns_name=$sns_name, sns_profile=$sns_profile WHERE sns_id='$sns_id'";
	
	$sql = "UPDATE $tbl_name SET member_device_token='$member_device_token' , member_login_date=UNIX_TIMESTAMP() WHERE member_srl='$member_srl'";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		// echo "update successfully";
		$array = array (
				'stat' => 1,
				'response' => array (
						'member_srl' => $member_srl,
						'member_device_token' => $member_device_token 
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
} else {
	$sql = "INSERT INTO $tbl_name (member_srl,  member_device_token, member_login_date)
			VALUES ('$member_srl', '$member_device_token', UNIX_TIMESTAMP())";
	
	if (mysqli_query ( $conn, $sql ) === TRUE) {
		// echo "New record created successfully";
		
		$array = array (
				'stat' => 1,
				'response' => array (
						'member_srl' => $member_srl,
						'member_device_token' => $member_device_token 
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
}
mysqli_close ( $conn );
?>